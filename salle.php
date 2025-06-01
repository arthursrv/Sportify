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

$stmt = $pdo->query("SELECT * FROM salle");
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Liste des salles - Sportify</title>
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

    h2.page-title {
      color: #0073e6;
      margin-bottom: 30px;
      text-align: center;
    }

    .salle {
      background: white;
      border-radius: 8px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      margin-bottom: 30px;
      padding: 20px;
      display: flex;
      gap: 20px;
      align-items: center;
    }
    .salle img {
      max-width: 300px;
      border-radius: 6px;
      object-fit: cover;
      flex-shrink: 0;
    }
    .salle-details {
      flex-grow: 1;
    }
    .salle-details p {
      margin: 5px 0;
      font-size: 1.1rem;
    }
    a.btn-services {
      display: inline-block;
      padding: 10px 20px;
      background-color: #0073e6;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      margin-top: 10px;
      transition: background-color 0.3s ease;
    }
    a.btn-services:hover {
      background-color: #005bb5;
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

    @media(max-width: 700px) {
      .salle {
        flex-direction: column;
        align-items: flex-start;
      }
      .salle img {
        max-width: 100%;
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
  <h2 class="page-title">Liste des salles</h2>

  <?php foreach ($salles as $salle): ?>
    <div class="salle">
      <img src="<?= htmlspecialchars($salle['image']) ?>" alt="Image de la salle <?= htmlspecialchars($salle['nom']) ?>" />
      <div class="salle-details">
        <h3><?= htmlspecialchars($salle['nom']) ?></h3>
        <p><strong>Numéro de salle :</strong> <?= htmlspecialchars($salle['numero']) ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($salle['telephone']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($salle['email']) ?></p>
        <a class="btn-services" href="services.php?salle_id=<?= urlencode($salle['id']) ?>">Nos services</a>
      </div>
    </div>
  <?php endforeach; ?>

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
