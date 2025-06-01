<?php
session_start();

$host = 'localhost';
$dbname = 'sportify';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (!isset($_GET['salle_id'])) die("Salle non spécifiée.");
$salle_id = intval($_GET['salle_id']);

$stmtSalle = $pdo->prepare("SELECT * FROM salle WHERE id = ?");
$stmtSalle->execute([$salle_id]);
$salle = $stmtSalle->fetch(PDO::FETCH_ASSOC);
if (!$salle) die("Salle introuvable.");

$stmtServices = $pdo->prepare("SELECT * FROM services WHERE salle_id = ?");
$stmtServices->execute([$salle_id]);
$services = $stmtServices->fetch(PDO::FETCH_ASSOC);
if (!$services) die("Aucun service trouvé.");

$horaire = json_decode($services['horaire'], true);
$reservations = json_decode($services['reservations'] ?? '{}', true);

$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
foreach ($jours as $j) {
    if (!isset($reservations[$j])) $reservations[$j] = ['AM' => [], 'PM' => []];
}

$creneaux = [
    'AM' => [],
    'PM' => []
];
for ($h = 9; $h <= 12; $h++) foreach (['00', '20', '40'] as $m) $creneaux['AM'][] = sprintf("%02d:%s", $h, $m);
for ($h = 14; $h <= 17; $h++) foreach (['00', '20', '40'] as $m) $creneaux['PM'][] = sprintf("%02d:%s", $h, $m);
$creneaux['PM'][] = "18:00";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_slot'])) {
    [$jour, $periode, $heure] = explode('-', $_POST['selected_slot']);
    if (!in_array($heure, $reservations[$jour][$periode])) {
        $reservations[$jour][$periode][] = $heure;
        $reservations_json = json_encode($reservations);
        $update = $pdo->prepare("UPDATE services SET reservations = ? WHERE salle_id = ?");
        $update->execute([$reservations_json, $salle_id]);
        echo "<script>alert('Réservation effectuée !'); window.location.href=window.location.href;</script>";
        exit;
    } else {
        echo "<script>alert('Ce créneau est déjà réservé.');</script>";
    }
}

$categorie = $_GET['categorie'] ?? null;
$categories = [
    'personnel' => 'Personnels de la salle de sport',
    'horaire' => 'Horaire de la gym',
    'regles' => 'Regles sur l’utilisation des machines',
    'nouveaux_clients' => 'Nouveaux clients',
    'alimentation' => 'Alimentation et nutrition'
];
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>Services - <?= htmlspecialchars($salle['nom']) ?></title>
  <style>
    body { font-family: Arial; max-width: 1000px; margin: 30px auto; background: #f7f9fc; padding: 0 15px; color: #333; }
    h1, h2 { color: #0073e6; }
    nav { margin-bottom: 30px; }
    nav a { margin-right: 15px; text-decoration: none; color: #0073e6; font-weight: bold; }
    nav a:hover { text-decoration: underline; }
    .service-content { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 3px 6px rgba(0,0,0,0.1); white-space: pre-wrap; }

    table { width: 100%; border-collapse: collapse; margin-top: 20px; text-align: center; }
    th, td { border: 1px solid #ccc; padding: 6px; }
    th { background-color: #e0e0e0; }
    td.booked { background: #007bff; color: white; }
    td.available { background: #fff; }
    button.slot-btn { width: 100%; padding: 4px; background: none; border: none; color: #0073e6; cursor: pointer; }
    button.slot-btn:disabled { background: #007bff; color: white; cursor: default; }

    .btn-back { display: inline-block; margin-top: 30px; padding: 10px 20px; background-color: #0073e6; color: white; border-radius: 6px; text-decoration: none; }
    .btn-back:hover { background-color: #005bb5; }
  </style>
</head>
<body>

<h1>Services de la salle : <?= htmlspecialchars($salle['nom']) ?></h1>

<nav>
  <?php foreach ($categories as $key => $label): ?>
    <a href="?salle_id=<?= $salle_id ?>&categorie=<?= $key ?>" <?= ($categorie === $key) ? 'style="text-decoration: underline;"' : '' ?>><?= $label ?></a>
  <?php endforeach; ?>
</nav>

<div class="service-content">
<?php
if ($categorie === 'horaire') {
    echo "<form method='POST'><table><tr>";
    foreach ($jours as $jour) echo "<th colspan='2'>" . ucfirst($jour) . "</th>";
    echo "</tr><tr>";
    foreach ($jours as $jour) echo "<th>AM</th><th>PM</th>";
    echo "</tr>";

    $max = max(count($creneaux['AM']), count($creneaux['PM']));
    for ($i = 0; $i < $max; $i++) {
        echo "<tr>";
        foreach ($jours as $jour) {
            foreach (['AM', 'PM'] as $periode) {
                $heure = $creneaux[$periode][$i] ?? '';
                if ($heure && in_array($periode, $horaire[$jour] ?? [])) {
                    $reserved = $reservations[$jour][$periode] ?? [];
                    $slot = "$jour-$periode-$heure";
                    if (in_array($heure, $reserved)) {
                        echo "<td class='booked'><button class='slot-btn' disabled>$heure</button></td>";
                    } else {
                        echo "<td class='available'><button type='submit' name='selected_slot' value='$slot' class='slot-btn'>$heure</button></td>";
                    }
                } else {
                    echo "<td></td>";
                }
            }
        }
        echo "</tr>";
    }
    echo "</table></form>";
} elseif ($categorie && isset($services[$categorie])) {
    echo nl2br(htmlspecialchars($services[$categorie]));
} else {
    echo "Veuillez selectionner une catégorie.";
}
?>
</div>

<a href="salle.php" class="btn-back">← Retour aux salles</a>

</body>
</html>
