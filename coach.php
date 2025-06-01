<?php
$pdo = new PDO('mysql:host=localhost;dbname=sportify;charset=utf8mb4', 'root', 'root');

if (isset($_GET['specialite'])) {
    $specialite = $_GET['specialite'];

    $stmt = $pdo->prepare("
        SELECT u.id, u.nom, u.prenom, u.email, c.photo, c.disponibilite, c.description, c.adresse, c.telephone
        FROM coach c
        JOIN utilisateurs u ON c.utilisateur_id = u.id
        WHERE c.specialite = ?
    ");
    $stmt->execute([$specialite]);
    $coach = $stmt->fetch();

    if ($coach) {
        echo "<div style='border:1px solid #ccc; padding:20px; width:700px; font-family:sans-serif;'>";

        echo "<div style='display:flex; align-items:center;'>";
        echo "<img src='" . $coach['photo'] . "' alt='Coach' width='120' style='margin-right:20px;'>";
        echo "<div>";
        echo "<h2 style='margin:0;'>" . strtoupper($coach['prenom'] . " " . $coach['nom']) . "</h2>";
        echo "<p style='margin:5px 0;'><strong>Coach, " . ucfirst($specialite) . "</strong><br>";
        echo "<strong>Adresse :</strong> " . $coach['adresse'] . "<br>";
        echo "<strong>Téléphone :</strong> " . $coach['telephone'] . "<br>";
        echo "<strong>Email :</strong> " . $coach['email'] . "</p>";
        echo "</div></div><br>";

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $dispos = json_decode($coach['disponibilite'], true); // Format JSON attendu

        echo "<table border='1' cellspacing='0' cellpadding='5' style='width:100%; border-collapse:collapse; text-align:center;'>";
        echo "<tr style='background:#e0f0e0;'><th style='background:#d0d8f0;'>Spécialité</th><th style='background:#d0d8f0;'>Médecin</th>";
        foreach ($jours as $jour) {
            echo "<th colspan='2'>" . $jour . "</th>";
        }
        echo "</tr>";

        echo "<tr><td colspan='2'></td>";
        foreach ($jours as $jour) {
            echo "<td style='font-weight:bold;'>AM</td><td style='font-weight:bold;'>PM</td>";
        }
        echo "</tr>";

        echo "<tr>";
        echo "<td>Coach de " . $specialite . "</td>";
        echo "<td>" . strtoupper($coach['nom']) . ", " . $coach['prenom'] . "</td>";

        foreach ($jours as $jour) {
            $jourKey = strtolower($jour);
            $am = in_array('AM', $dispos[$jourKey] ?? []);
            $pm = in_array('PM', $dispos[$jourKey] ?? []);

            echo "<td style='background:" . ($am ? 'white' : 'black') . "; color:" . ($am ? 'black' : 'black') . "; height:30px;'></td>";
            echo "<td style='background:" . ($pm ? 'white' : 'black') . "; color:" . ($pm ? 'black' : 'black') . "; height:30px;'></td>";
        }

        echo "</tr></table>";

        // Boutons
        echo "<br><div style='display:flex; gap:10px;'>";
        echo "<a href='rdv.php?coach_id=" . $coach['id'] . "'>
        <button style='padding:10px; background:green; color:white; border:none; border-radius:5px;'>Prendre un RDV</button>
      </a>";

        echo "<button style='padding:10px; background:lightblue; border:none; border-radius:5px;'>Communiquer avec le coach</button>";
        echo "<button style='padding:10px; background:lightgray; border:none; border-radius:5px;'>Voir son CV</button>";
        echo "</div>";

        echo "</div>";
    } else {
        echo "Aucun coach trouve pour cette activité.";
    }
} else {
    echo "Aucune activité selectionnee.";
}
?>
