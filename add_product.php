<?php
header('Content-Type: application/json');
require_once './connect_database.php';

$response = array();

if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $image = $_FILES['image'];
    $imageFileName = $image['name'];
    $imageTempPath = $image['tmp_name'];

    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if ($title && $description && $price !== false && strlen($title) <= 255 && strlen($description) <= 1000) {
        $title = htmlspecialchars($title);
        $description = htmlspecialchars($description);
        $price = floatval($price);

        $uploadDirectory = 'images/'; 
        $imageFilePath = $uploadDirectory . basename($imageFileName); 

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileExtension = strtolower(pathinfo($imageFilePath, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $response['error'] = true;
            $response['message'] = "Type de fichier non autorisé. Seules les images au format JPG, JPEG, PNG et GIF sont autorisées.";
        } elseif (move_uploaded_file($imageTempPath, $imageFilePath)) {
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
        $response['message'] = "Les données saisies sont invalides ou dépassent la longueur autorisée.";
    }
} else {
    $response['error'] = true;
    $response['message'] = "Veuillez renseigner les informations.";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
