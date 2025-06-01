<?php
$host = 'localhost';
$dbname = 'sportify';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$sql = "SELECT c.*, u.nom, u.prenom 
        FROM coach c 
        JOIN utilisateurs u ON c.utilisateur_id = u.id 
        WHERE c.specialite IN ('tennis', 'Fitness', 'Biking', 'Cardio-Training', 'Cours Collectifs')";

$stmt = $pdo->query($sql);
$coachs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Activités Sportives - Sportify</title>
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
      max-width: 900px;
      margin: 20px auto;
      padding: 0 20px;
    }

    h2 {
      text-align: center;
      color: #0073e6;
      margin-bottom: 30px;
    }

    .activity {
      text-align: center;
      margin: 30px 0;
    }

    .activity a {
      display: inline-block;
      margin: 10px;
      padding: 12px 24px;
      background: #0073e6;
      color: white;
      text-decoration: none;
      border-radius: 8px;
    }

    .activity a:hover {
      background: #005bb5;
    }

    footer {
      background-color: #0073e6;
      color: white;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
      margin-top: 40px;
    }

    footer a {
      color: #ffcc00;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @media(max-width: 600px) {
      nav ul {
        flex-direction: column;
        gap: 10px;
      }
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
  <h2>Activités Sportives</h2>
  <div class="activity">
    <a href="coach.php?specialite=tennis">Tennis</a>
    <a href="coach.php?specialite=Fitness">Fitness</a>
    <a href="coach.php?specialite=Biking">Biking</a>
    <a href="coach.php?specialite=Cardio-Training">Cardio-Training</a>
    <a href="coach.php?specialite=Cours Collectifs">Cours Collectifs</a>
  </div>
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
