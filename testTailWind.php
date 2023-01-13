<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/output.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<!-- Pin to bottom right corner -->
<?php
  require_once('BDD.php');
  $bdd = new connectBDD();
?>

<div class="relative h-32 w-32 hover:cursor-alias ...">
  <div class="absolute bottom-0 left-0 h-16 w-16 ">09</div>
  <?php
if (isset($_POST['submit'])) {

    // On récupère les informations du fichier upload par l'utilisateur
    $file = $_FILES['file'];
    // On récupère le nom du fichier
    $fileName = $_FILES['file']['name'];
    // On récupère le chemin temporaire du fichier
    $fileTmpName = $_FILES['file']['tmp_name'];
    // On récupère la taille du fichier
    $fileSize = $_FILES['file']['size'];
    // On récupère le code d'erreur du fichier
    $fileError = $_FILES['file']['error'];
    // On récupère le type du fichier
    $fileType = $_FILES['file']['type'];

    // On récupère l'extension du fichier
    $fileExt = explode('.', $fileName);
    // On récupère l'extension du fichier en minuscule
    $fileActualExt = strtolower(end($fileExt));

    // On définit les extensions autorisées
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 2000000) {
                // On créé un nom unique pour le fichier
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                // On déplace le fichier dans le dossier imgplayers
                $fileDestination = '../imgplayers/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                // succès
            } else {
                echo "Votre fichier est trop volumineux! taille max : 2Mo";
            }
        } else {
            echo "Erreur de téléchargement, veuillez réessayer.";
        }
    } else {
        echo "Vous ne pouvez pas télécharger ce type de fichier! Formats acceptés : jpg, jpeg, png. Taille max : 2M";
    }
}

?>

<form method="post" action="testTailWind.php" enctype="multipart/form-data">
  <input type="file" name="file">
  <input name="submit" type="submit" value="Télécharger">
</form>
</div>
</body>
</html> 