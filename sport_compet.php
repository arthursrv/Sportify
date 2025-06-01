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
  <meta charset="UTF-8">
  <title>Activités Sportives</title>
  <style>
    body {
      font-family: Arial;
      margin: 0;
      background-color: #f0f0f5;
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
      max-width: 1000px;
      margin: 40px auto;
      text-align: center;
    }

    h2 {
      color: #003366;
      margin-bottom: 20px;
    }

    .activity {
      margin: 20px;
    }

    .activity a {
      display: inline-block;
      margin: 10px;
      padding: 12px 24px;
      background: #0073e6;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-size: 1.1rem;
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
    }
    footer a {
      color: #ffcc00;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
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
  <h2>Choisissez une activité sportive</h2>
  <div class="activity">
    <a href="coach.php?specialite=Tennis">Tennis</a>
    <a href="coach.php?specialite=Rugby">Rugby</a>
    <a href="coach.php?specialite=Football">Football</a>
    <a href="coach.php?specialite=Basketball">Basketball</a>
    <a href="coach.php?specialite=Natation">Natation</a>
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
