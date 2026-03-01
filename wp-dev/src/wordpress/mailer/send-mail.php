<?php

require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  // global data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $entreprise = $_POST['entreprise'];
  $tel = $_POST['tel'];
  $email = $_POST['email'];
  $message = nl2br(stripslashes($_POST['message']));
  $pro = isset($_POST['pro']);
  $opt_in = $_POST['opt-in'];

  
  /* ========================================================================= *\
  **  === MAIL ADMIN                                                           *| 
  \* ========================================================================= */

  // config
  $headers[] = 'From: xxxx <wordpress@xxxx.com>';
  $headers[] = 'Content-Type: text/html; charset=UTF-8';
  $to = 'contact@xxxx.com';
  $subject = 'Nouveau Mail';

  // body
  $template = "";

  if($pro) {
    $template .= "Je suis un professionnel de la distribution";
    $template .= "<br>";

    if(strlen($entreprise)) {
      $template .= "Entreprise : " . $entreprise;
      $template .= "<br>";
    }

    if(strlen($tel)) {
      $template .= "Tél : " . $tel;
      $template .= "<br>";
    }
  }

  $template .= "Mail : " . $email;
  $template .= "<br>";
  $template .= "Prénom : " . $prenom;
  $template .= "<br>";
  $template .= "Nom : " . $nom;
  $template .= "<br>";

  if(strlen($message)) {
    $template .= "Message : ";
    $template .= "<br>";
    $template .= $message;
    $template .= "<br>";
  }

  //body
  $body = $template;

  // print_r(json_encode($body, JSON_PRETTY_PRINT));

  // send mail
  // wp_mail($to, $subject, $body, $headers);

  // /* ========================================================================= *\
  // **  === MAIL USER                                                            *| 
  // \* ========================================================================= */
  
  // config
  $to = $email;
  $subject = $prenom . ', merci pour votre message';

  // body
  $template = "";
  $template .= "Bonjour " . $prenom . ",";
  $template .= "<br>";
  $template .= "Merci de nous avoir écrit, votre message a bien été envoyé";
  $template .= "<br>";
  $template .= "Votre message : " . $message;
  $template .= "<br>";

  //body
  $body = $template;

  print_r(json_encode($body, JSON_PRETTY_PRINT));

  // send mail
  // wp_mail($to, $subject, $body, $headers);

} else {
  header('HTTP/1.0 403 Forbidden');
}