<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=sportify;charset=utf8mb4', 'root', 'root');
$notification = null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disponibilités Coach</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0; padding: 0;
        background: #f7f9fc;
        color: #333;
      }
      header {
        background-color: #0073e6;
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      header h1 {
        margin: 0;
        font-size: 1.8rem;
      }
      nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
        padding: 0;
      }
      nav ul li a {
        color: white;
        text-decoration: none;
        font-weight: bold;
      }
      nav ul li a:hover {
        text-decoration: underline;
      }

      main {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
      }

      table { border-collapse: collapse; text-align: center; margin: 20px 0; }
      th, td { border: 1px solid #666; padding: 6px; width: 70px; height: 40px; }
      td.available { background-color: white; cursor: pointer; color: black; }
      td.booked { background-color: #007bff; color: white; font-weight: bold; }
      td.unavailable { background-color: black; color: black; }
      button.slot-btn { width: 100%; height: 100%; border: none; background: none; cursor: pointer; font-weight: bold; }
      button.slot-btn:disabled { cursor: default; }
      td.booked button.slot-btn:disabled {
          background-color: #007bff !important;
          color: white !important;
          font-weight: bold;
          cursor: default;
      }

      footer {
        margin-top: 40px;
        background-color: #f0f0f0;
        text-align: center;
        padding: 10px;
      }

      .btn-deconnexion {
        background-color: #ff4d4d;
        border: none;
        padding: 8px 12px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
      }
      .btn-deconnexion:hover {
        background-color: #e04343;
      }
    </style>
</head>
<body>

<header>
  <h1>Sportify</h1>
  <nav>
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="tout_parcourir.php">Tout Parcourir</a></li>
      <li><a href="recherche.php">Recherche</a></li>
      <li><a href="rendez_vous.php">Rendez-vous</a></li>
      <li><a href="votre_compte.php">Votre Compte</a></li>
    </ul>
  </nav>
</header>

<main>
<?php
if (isset($_GET['coach_id'])) {
    $coach_id = $_GET['coach_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_slot'])) {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['client', 'admin'])) {
            $notification = "Seuls les clients ou administrateurs peuvent prendre un rendez-vous. Veuillez vous connecter.";
        } else {
            $slot = $_POST['selected_slot'];
            [$jour, $periode, $heure] = explode('-', $slot);
            $date_rdv = $jour;
            $heure_rdv = $heure;

            $check = $pdo->prepare("SELECT COUNT(*) FROM rendezvous WHERE coach_id = ? AND date_rdv = ? AND heure_rdv = ?");
            $check->execute([$coach_id, $date_rdv, $heure_rdv]);
            $alreadyTaken = $check->fetchColumn();

            if (!$alreadyTaken) {
                $stmt = $pdo->prepare("INSERT INTO rendezvous (client_id, coach_id, date_rdv, heure_rdv, statut) VALUES (?, ?, ?, ?, 'en attente')");
                $stmt->execute([$_SESSION['user_id'], $coach_id, $date_rdv, $heure_rdv]);
                $notification = "Le créneau $slot est maintenant pris.";
            } else {
                $notification = "Ce créneau est déjà pris.";
            }
        }
    }

    $stmt = $pdo->prepare("SELECT u.nom, u.prenom, c.disponibilite, c.specialite FROM coach c JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.utilisateur_id = ?");
    $stmt->execute([$coach_id]);
    $coach = $stmt->fetch();

    if ($coach) {
        $specialite = $coach['specialite'];
        $dispos = json_decode($coach['disponibilite'], true);

        $ordreJours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $jours = array_filter($ordreJours, fn($jour) => array_key_exists($jour, $dispos));

        $stmt = $pdo->prepare("SELECT date_rdv, heure_rdv FROM rendezvous WHERE coach_id = ?");
        $stmt->execute([$coach_id]);
        $rdvs_pris = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $slotsPris = [];
        foreach ($rdvs_pris as $rdv) {
            $jour_fr = strtolower($rdv['date_rdv']);
            $heure = substr($rdv['heure_rdv'], 0, 5);
            $periode = ((int)substr($heure, 0, 2) < 13) ? 'AM' : 'PM';
            $slotsPris[] = "$jour_fr-$periode-$heure";
        }

        $creneauxAM = [];
        for ($h = 9; $h <= 12; $h++) {
            foreach (['00', '20', '40'] as $m) {
                $creneauxAM[] = sprintf("%02d:%s", $h, $m);
            }
        }
        $creneauxPM = [];
        for ($h = 14; $h <= 17; $h++) {
            foreach (['00', '20', '40'] as $m) {
                $creneauxPM[] = sprintf("%02d:%s", $h, $m);
            }
        }
        $creneauxPM[] = "18:00";

        echo "<h2>Disponibilités de {$coach['prenom']} {$coach['nom']} – $specialite</h2>";
        echo "<form method='POST'><table>";

        echo "<tr>";
        foreach ($jours as $jour) {
            echo "<th colspan='2'>" . ucfirst($jour) . "</th>";
        }
        echo "</tr><tr>";
        foreach ($jours as $jour) {
            echo "<th>AM</th><th>PM</th>";
        }
        echo "</tr>";

        $maxCreneaux = max(count($creneauxAM), count($creneauxPM));
        for ($i = 0; $i < $maxCreneaux; $i++) {
            echo "<tr>";
            foreach ($jours as $jour) {
                $heureAM = $creneauxAM[$i] ?? null;
                $heurePM = $creneauxPM[$i] ?? null;

                foreach (['AM' => $heureAM, 'PM' => $heurePM] as $periode => $heure) {
                    if ($heure) {
                        $slot = "$jour-$periode-$heure";
                        $isDispo = in_array($periode, $dispos[$jour] ?? []);
                        if ($isDispo) {
                            if (in_array($slot, $slotsPris)) {
                                echo "<td class='booked'><button type='button' class='slot-btn' disabled>$heure</button></td>";
                            } else {
                                echo "<td class='available'><button type='submit' name='selected_slot' value='$slot' class='slot-btn'>$heure</button></td>";
                            }
                        } else {
                            echo "<td class='unavailable'></td>";
                        }
                    } else {
                        echo "<td class='unavailable'></td>";
                    }
                }
            }
            echo "</tr>";
        }

        echo "</table></form>";
    } else {
        echo "<p>Aucun coach trouvé pour cet ID.</p>";
    }
} else {
    echo "<p>Aucun coach sélectionné.</p>";
}

if ($notification) {
    echo "<script>alert(" . json_encode($notification) . ");</script>";
}
?>
</main>

<footer>
  <p>Contactez-nous : 
    <a href="mailto:contact@sportify.com">contact@sportify.com</a>
    Téléphone : 01 23 45 67 89
    Adresse : 12 rue du commerce, 75015 Paris
  </p>
</footer>

</body>
</html>
