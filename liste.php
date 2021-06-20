<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="public/css/style.css">
  <!-- FONT -->
  <link href="https://fonts.googleapis.com/css2?family=Shadows+Into+Light&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Caveat&display=swap" rel="stylesheet">
  <!-- SCRIPT -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="public/js/liste.js" type="text/javascript"></script>

  <title>Listing of tests done</title>
</head>
<body>

  <?php
    if ($_GET['v'] != 'confirm' ){
      header('Location: http://survey.mapir.net');
      exit();
    }
  ?>

  <header class="container-fluid blanc">
    <div class="entete">
      <img src="public/img/Vestas_logo.png"/>
      <span id="signMAPIR">by M<a href='mailto:mapir@vestas.com'>@</a>PiR</span>
    </div>
    <div class="trait"></div>
  </header>

  <section id="done-candidat" class="container-fluid blanc">
    <h2>Liste des candidats aillant fait un test :</h2>
    <ul id="done-candidat_listing" class="container">
      <?php
        require 'public/php/database.php';
        $db = Database::connect();
        $tempo = $db->query('SELECT `candidat`, MIN(CONCAT(DAY(`date`),"/",MONTH(`date`),"/",YEAR(`date`))) AS `date`, MIN(`region`) AS `area` FROM `saves` GROUP BY `candidat`');
        Database::disconnect();
        while ($row = $tempo->fetch(PDO::FETCH_ASSOC)){
          echo '<li class="container-fluid tempo">
                  <div class="done-candidat_presentation row">
                    <div class="done-candidat_nom col-5">'.$row["candidat"].'</div>
                    <div class="done-candidat_date col-3">'.$row["date"].'</div>
                    <div class="done-candidat_region col-3">'.$row["area"].'</div>
                  </div>
                </li>';
        }
      ?>
    </ul>
  </section>

  <footer class="container-fluid">
    <div class="trait-reverse"></div>
    <ul class="footer-choix row">
      <li class="listeFooter"><a href="exam.php?v=confirm">Retour - Lancer un test</a></li>
    </ul>
    <p class="footer-end">Entièrement codé par M@PiR</p>
  </footer>

</body>
</html>