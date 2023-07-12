<?php
// Votre logique de traitement de la requête ici

// Exemple : Renvoyer une réponse JSON
header('Content-Type: application/json');

$response = [
  'message' => 'Bienvenue sur mon API PHP !',
  'timestamp' => time()
];

echo json_encode($response);
