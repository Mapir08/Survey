<?php

// CONNEXION MYSQL
try{
$database = new PDO('mysql:host=localhost;dbname=survey','root','root');
}
catch(Exception $e){
  die('ERROR: '.$e->getMessage());
}

// Initialise le tableau de confirmation
$confirmation = array(
  'codeSuccess' => false,
  'nameSuccess' => false,
  'areaSuccess' => false,
  'candidat' => verif($_POST['log_name']),
  'area' => 'vide@vestas.com'
);

// ACTION AU 'POST' DU LOG
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $confirmation['codeSuccess'] = true;
  $confirmation['nameSuccess'] = true;
  $confirmation['areaSuccess'] = true;
  if ($_POST['log_name'] == ''){
    $confirmation['nameSuccess'] = false;
  }
  if ($_POST['log_area'] == ''){
    $confirmation['areaSuccess'] = false;
  }
  // Création de la liste des question en fonction du CODE envoyé
  if ($_POST['log_code'] == '' OR strpos($_POST['log_code'],'V')!=0 OR !strpos($_POST['log_code'],'E') OR !strpos($_POST['log_code'],'H')){
    $confirmation['codeSuccess'] = false;
  }else{
    // DEBUT de l'extraction du type et nombre de question
    $tempo = explode('V' , $_POST['log_code']);
    $pourRecupV  = explode('E' , $tempo[1]);
    $code['V'] = $pourRecupV[0]; // V est OK
    $pourRecupE = explode('H' , $pourRecupV[1]);
    $code['E'] = $pourRecupE[0]; // E est Ok
    $code['H'] = $pourRecupE[1]; // H est Ok
    // FIN de l'extraction du type et nombre de question

    // Créer une variable pour avoir le nombre maximum de E et H
    // Laisser la limite de question à 30
    // A SAVOIR : les types de question ne sont pas mélangé, les E puis les H

    if ( ($code['E'] + $code['H']) > 30 OR $code['E'] > 23 OR $code['H'] > 7){
      $confirmation['codeSuccess'] = false;
    }else{
      // Ajout des questions E en fonction de leur nombre et en Random
      $tempo = $database->query('SELECT question, img, reponse, choix1, choix2, choix3, choix4 FROM questions WHERE level='.$code['V'].' AND type="E" ORDER BY rand() LIMIT '.$code['E'] );
      $i = 0;
      while ($row = $tempo->fetch(PDO::FETCH_ASSOC)){
        $questionnaire[$i] = $row;
        $i+=1;
      }
      // Ajout des questions H en fonction de leur nombre et en Random
      $tempo = $database->query('SELECT question, img, reponse, choix1, choix2, choix3, choix4 FROM questions WHERE level='.$code['V'].' AND type="H"  ORDER BY rand() LIMIT '.$code['H']);
      while ($row = $tempo->fetch(PDO::FETCH_ASSOC)){
        $questionnaire[$i] = $row;
        $i+=1;
      }
    }
  }
  // Rechercher le contact dans la BDD en fonction de l'area
  $tempo = $database->query('SELECT area, contact FROM regions');
  while ($row = $tempo->fetch()){
    if ($row['area'] == $_POST['log_area']){
      $confirmation['area'] = $row['contact'];
    }
  }
  // Les 2 tableaux à retourner
  $retour = array(
    'questionnaire' => $questionnaire,
    'confirmation' => $confirmation
  );
  echo json_encode($retour); 
}

function verif($var){
  $var=strip_tags($var);
  return $var;
}
?>