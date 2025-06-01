<?php

session_start();

$host = 'localhost';
$dbname = 'sportify';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscrire'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $type = $_POST['type_utilisateur'];

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $type]);

    $successMessage = "Inscription réussie !";
}

$coachs = $pdo->query("SELECT * FROM utilisateurs WHERE type_utilisateur = 'coach'")->fetchAll();
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Accueil - Sportify</title>
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

    .welcome {
      text-align: center;
      margin-bottom: 40px;
    }
    .welcome h2 {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #0073e6;
    }
    .welcome p {
      font-size: 1.1rem;
      color: #555;
    }

    .event-section {
      background-color: white;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 3px 6px rgb(0 0 0 / 0.1);
      margin-bottom: 40px;
    }
    .event-section h3 {
      margin-bottom: 15px;
      color: #0073e6;
    }
    .event-description {
      font-size: 1rem;
      margin-bottom: 10px;
    }
    .event-date {
      font-style: italic;
      color: #888;
    }

    .carousel {
      position: relative;
      overflow: hidden;
      border-radius: 6px;
      box-shadow: 0 3px 6px rgb(0 0 0 / 0.1);
      margin-bottom: 40px;
    }
    .carousel-images {
      display: flex;
      transition: transform 0.4s ease-in-out;
    }
    .carousel-images img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      border-radius: 6px;
      flex-shrink: 0;
    }
    .carousel-controls {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      transform: translateY(-50%);
      pointer-events: none;
    }
    .carousel-button {
      background: rgba(0,0,0,0.5);
      border: none;
      color: white;
      font-size: 2rem;
      padding: 5px 15px;
      cursor: pointer;
      pointer-events: all;
      border-radius: 50%;
      user-select: none;
      transition: background 0.3s;
    }
    .carousel-button:hover {
      background: rgba(0,0,0,0.8);
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
  <section class="welcome">
    <h2>Bienvenue sur Sportify</h2>
    <p>Votre plateforme de consultation sportive en ligne. Decouvrez nos evenements, specialistes et actualites sportives chaque semaine.</p>
  </section>

  <section class="event-section" aria-label="Evenement de la semaine">
    <h3>Événement de la semaine</h3>
    <p class="event-description">
      Porte ouverte de Sportify. Venez rencontrer nos spécialistes et decouvrir nos installations sportives modernes.
    </p>
    <p class="event-date">Du 2 au 7 juin 2025</p>
  </section>

  <section class="carousel" aria-label="Carrousel des spécialistes sportifs">
    <div class="carousel-images" id="carousel-images">
      <img src="roger_federer.jpg" alt="Roger Federer" />
      <img src="curry.png" alt="Steph Curry" />
      <img src="dupont.jpg" alt="Antoine Dupont" />
    </div>
    <div class="carousel-controls">
      <button class="carousel-button" id="prev" aria-label="Image précédente">&#10094;</button>
      <button class="carousel-button" id="next" aria-label="Image suivante">&#10095;</button>
    </div>
  </section>
</main>

<footer>
  <p>Contactez-nous : 
    <a href="mailto:contact@sportify.com">contact@sportify.com</a>
    Telephone : 01 23 45 67 89 
    Adresse : 12 rue du commerce, 75015 Paris
  </p>
</footer>

<script>
  const carouselImages = document.getElementById('carousel-images');
  const images = carouselImages.querySelectorAll('img');
  const prevBtn = document.getElementById('prev');
  const nextBtn = document.getElementById('next');
  let currentIndex = 0;

  function updateCarousel() {
    const offset = -currentIndex * 100;
    carouselImages.style.transform = `translateX(${offset}%)`;
  }

  prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex === 0) ? images.length -1 : currentIndex -1;
    updateCarousel();
  });

  nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex === images.length -1) ? 0 : currentIndex +1;
    updateCarousel();
  });
</script>

</body>
</html>
