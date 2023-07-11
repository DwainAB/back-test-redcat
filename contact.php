<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupérer les valeurs du formulaire
  $lastname = $_POST['lastname'];
  $firstname = $_POST['firstname'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Adresse e-mail de destination
  $to = 'dwain93290@icloud.com';

  // Sujet de l'e-mail
  $email_subject = 'Nouveau message depuis le formulaire de contact';

  // Construire le corps de l'e-mail
  $email_body = "Nom: $lastname\n";
  $email_body .= "Prénom: $firstname\n";
  $email_body .= "Téléphone: $phone\n";
  $email_body .= "E-mail: $email\n";
  $email_body .= "Sujet: $subject\n";
  $email_body .= "Message:\n$message\n";

  // Entêtes de l'e-mail
  $headers = "From: $email\r\n";
  $headers .= "Reply-To: $email\r\n";

  // Envoyer l'e-mail
  if (mail($to, $email_subject, $email_body, $headers)) {
    echo 'Le message a été envoyé avec succès.';
  } else {
    echo 'Une erreur est survenue lors de l\'envoi du message.';
  }
} else {
  echo 'Une erreur est survenue. Veuillez réessayer.';
}
?>
