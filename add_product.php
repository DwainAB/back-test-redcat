<?php
header('Content-Type: application/json');
require_once './connect_database.php';


$response = array();

if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Récupérer les informations sur l'image
    $image = $_FILES['image'];
    $imageFileName = $image['name'];
    $imageTempPath = $image['tmp_name'];


    // Valider les données d'entrée
    $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if ($title && $description && $price !== false) {
        // Échapper les données de sortie
        $title = htmlspecialchars($title);
        $description = htmlspecialchars($description);
        $price = floatval($price);

        // Déplacer l'image vers un dossier de destination
        $uploadDirectory = 'images/'; // Remplacez par le chemin de votre dossier d'images
        $imageFilePath = $uploadDirectory . $imageFileName;

        if (move_uploaded_file($imageTempPath, $imageFilePath)) {
            // Insérer les données dans la base de données
            $requete = $con->prepare('INSERT INTO product (title, description, price, image) VALUES (?, ?, ?, ?)');
            $requete->bind_param('ssds', $title, $description, $price, $imageFilePath);

            if($requete->execute()){
                $response['error'] = false;
                $response['message'] = "Nouveau produit ajouté avec succès !";
            } else {
                $response['error'] = true;
                $response['message'] = "Le produit n'a pas pu être ajouté";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Une erreur s'est produite lors du téléchargement de l'image";
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Les données saisies sont invalides.";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Veuillez renseigner les informations.";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
