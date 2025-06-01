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
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tout Parcourir - Sportify</title>
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

    nav ul li {
      margin: 0;
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

    .category {
      background-color: white;
      border-radius: 6px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
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
  <h2>Tout Parcourir</h2>

  <div class="category">
    <h3><a href="activites_sportives.php">Activites Sportives</a></h3>
    <p>Explorez toutes les disciplines sportives proposees sur Sportify : Musculation, Fitness, Biking, Cardio-Training, Cours Collectifs.</p>
  </div>

  <div class="category">
    <h3><a href="sport_compet.php">Sports de Compétition</a></h3>
    <p>Découvrez les sports competitifs : Basketball, Football, Rugby, Tennis, Natation, Plongeon.</p>
  </div>

  <div class="category">
    <h3><a href="salle.php">Salle de sport Omnes</a></h3>
    <p>Consultez les services, horaires, regles d’utilisation, et prenez rendez-vous avec un coach pour visiter ou utiliser la salle.</p>
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
