<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Modifier un joueur</title>
</head>

<?php

// On démarre la session
session_start();

// On vérifie si la personne est connectée, sinon on la redirige vers la page de connexion
if ($_SESSION['email'] == '') {
    header('Location: login.php');
}

require_once('player.php');
$player = new Player();

//Decryptage de l'id du joueur
$idPlayer = base64_decode($_GET['id']);
$idPlayer = openssl_decrypt($idPlayer, "aes-256-ecb", "toto");

//Récupération des données du joueur
$p = $player->getPlayer($idPlayer);

while ($data = $p->fetch()) {
    $name = $data['prenom'];
    $lastname = $data['nom'];
    $picture = $data['photo'];
    $license = $data['numero_de_licence'];
    $birthday = $data['date_de_naissance'];
    $weight = $data['poid'];
    $size = $data['taille'];
    $position = $data['poste_prefere'];
    $comment = $data['commentaire'];
    $state = $data['statut'];
}



$msg_error = "";
if (isset($_POST["edit"])) {
    //Tout les champs sont remplis
    if (!empty($_POST['name']) && !empty($_POST['lastname']) && !empty($_POST['license']) && !empty($_POST['birthday']) && !empty($_POST['weight']) && !empty($_POST['size']) && !empty($_POST['position'])) {
        //Format de la licence incorrect (00000AA)
        if (preg_match('/^[0-9]{5}[A-Z]{2}$/', $_POST['license'])) {
            //Date supérieur à 18 ans
            if (date_diff(date_create($_POST['birthday']), date_create('today'))->y >= 18) {
                //Joueur deja existant 
                if (!$player->playerExistUpdate($_POST['license'], $_POST['name'], $_POST['lastname'], $idPlayer)) {
                    if (empty($_POST['state'])) {
                        $pState = null;
                    } else {
                        $pState = $_POST['state'];
                    }
                    if (empty($_POST['comment'])) {
                        $pComment = null;
                    } else {
                        $pComment = $_POST['comment'];
                    }
                    if (empty($_FILES['picture'])) {
                        $ppicture = $picture;
                    } else {
                        $ppicture = $_FILES['picture'];
                    }
                    // On récupère les informations du fichier upload par l'utilisateur
                    $file = $_FILES['picture'];
                    // On récupère le nom du fichier
                    $fileName = $_FILES['picture']['name'];
                    // On récupère le chemin temporaire du fichier
                    $fileTmpName = $_FILES['picture']['tmp_name'];
                    // On récupère la taille du fichier
                    $fileSize = $_FILES['picture']['size'];
                    // On récupère le code d'erreur du fichier
                    $fileError = $_FILES['picture']['error'];
                    // On récupère le type du fichier
                    $fileType = $_FILES['picture']['type'];

                    // On récupère l'extension du fichier
                    $fileExt = explode('.', $fileName);
                    // On récupère l'extension du fichier en minuscule
                    $fileActualExt = strtolower(end($fileExt));

                    // On définit les extensions autorisées
                    $allowed = array('jpg', 'jpeg', 'png');

                    if (in_array($fileActualExt, $allowed)) {
                        if ($fileError === 0) {
                            if ($fileSize < 5000000) {
                                // On créé un nom unique pour le fichier
                                $fileNameNew = uniqid('', true).".".$fileActualExt;
                                // On déplace le fichier dans le dossier imgplayers
                                $fileDestination = '../imgplayers/'.$fileNameNew;
                                move_uploaded_file($fileTmpName, $fileDestination);
                                $ppicture = $fileNameNew;
                                // succès
                            } else {
                                echo "Votre fichier est trop volumineux! taille max : 5Mo";
                            }
                        } else {
                            echo "Erreur de téléchargement, veuillez réessayer.";
                        }
                    } else {
                        echo "Vous ne pouvez pas télécharger ce type de fichier! Formats acceptés : jpg, jpeg, png. Taille max : 5M";
                    }
                    //Modification du joueur
                    $player->updatePlayer($idPlayer, $_POST['name'], $_POST['lastname'], $ppicture, $_POST['license'], $_POST['birthday'], $_POST['weight'], $_POST['size'], $_POST['position'], $pState, $pComment);
                    //Affichage des modifications effectuées
                    $p = $player->getPlayer($idPlayer);
                    while ($data = $p->fetch()) {
                        $name = $data['prenom'];
                        $lastname = $data['nom'];
                        $picture = $data['photo'];
                        $license = $data['numero_de_licence'];
                        $birthday = $data['date_de_naissance'];
                        $weight = $data['poid'];
                        $size = $data['taille'];
                        $position = $data['poste_prefere'];
                        $comment = $data['commentaire'];
                        $state = $data['statut'];
                    }
                    $msg_error = "Joueur modifié !";
                } else {
                    $msg_error = "Joueur déjà existant";
                }
            } else {
                $msg_error = "Le joueur doit avoir plus de 18 ans";
            }
        } else {
            $msg_error = "Format de la licence incorrect (00000AA)";
        }
    } else {
        $msg_error = "Veuillez remplir tous les champs";
    }
}

if (isset($_POST["return"])) {
    header("location: displayPlayers.php");
}

?>

<body>
    <main>
        <!-- Navbar latérale -->
        <nav class="flex flex-col justify-between w-60 h-screen fixed bg-gradient-to-br from-violet-700 to-violet-900 text-white border-slate-500 border-r-[1px]">
            <div class="mx-4 flex items-center border-b border-purple-50 border-opacity-25">
                <img class="w-24" src="../img/team-logo.png" alt="Logo United">
                <span class="text-2xl">United Chasetag</span>
            </div>
            <ul class="flex justify-start h-full pt-32 flex-col leading-10 text-lg">
                <li class="pl-4 py-2 flex gap-2 items-center bg-violet-700 cursor-pointer border-l-2 font-medium"><i class="flex fi fi-rr-users-alt"></i></i><a href="displayPlayers.php" class="inline-flex w-full">Effectif</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-user-add"></i><a href="addPlayer.php" class="inline-flex w-full">Ajouter un joueur</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-trophy"></i><a href="displayMatchs.php" class="inline-flex w-full">Matchs</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-add-document"></i><a href="addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-stats"></i><a href="displayStatistics.php" class="inline-flex w-full">Statistiques</a></li>
            </ul>
            <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
                <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
            </div>
        </nav>
        <section class="grid place-items-center ml-72 mr-12">
            <div class="my-6 px-9  border-2 border-purple-800 rounded ">
                <h2 class="m-5 text-4xl font-bold text-center">Modifier <?php echo $name . ' ' . $lastname; ?></h2>
                <form class="block w-full max-w-lg mb-10" action="editPlayer.php?id=<?php echo $_GET["id"] ?>" method="post" enctype="multipart/form-data">

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Nom
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" id="grid-last-name" name="lastname" type="text" value="<?php echo $lastname; ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                Prénom
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-first-name" name="name" type="text" value="<?php echo $name; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase  tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-picture">
                                Photo
                            </label>
                            <input class="w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-picture" name="picture" type="file" accept="image/png, image/jpeg">
                        </div>
                        <div class="w-full md:w-1/2 px-3 flex items-center justify-center">
                            <img class="w-16 h-16 rounded-full" src="<?php echo '../imgPlayers/' . $picture; ?>" alt="Photo de <?php echo $lastname . ' ' . $name; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-license-number">
                                Numéro de licence
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-license-number" name="license" type="text" value="<?php echo $license; ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-birthday">
                                Date de naissance
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-birthday" name="birthday" type="date" value="<?php echo $birthday; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-weight">
                                POIDS (en KG)
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-weight" name="weight" type="number" min="40" max="150" value="<?php echo $weight; ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-size">
                                Taille (en cm)
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-size" name="size" type="number" min="130" max="220" value="<?php echo $size; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-favorite_position">
                                Poste préféré
                            </label>
                            <div class="relative">
                                <select class="block w-full bg-gray-200 border  text-gray-700 border-purple-800 rounded  py-3 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-favorite_position" name="position">
                                    <?php
                                    if ($position == 'Chat') {
                                        echo '<option value ="Chat" selected>Chat</option>';
                                        echo '<option value="Souris">Souris</option>';
                                    } elseif ($position == 'Souris') {
                                        echo '<option value ="Chat">Chat</option>';
                                        echo '<option value="Souris" selected>Souris</option>';
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                                Statut
                            </label>
                            <div class="relative">
                                <select class="block w-full bg-gray-200 border  text-gray-700 border-purple-800 rounded  py-3 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state" name="state">
                                    <?php
                                    switch ($state) {
                                        case null:
                                            echo '<option value="null" selected>Non Renseigné</option>';
                                            echo '<option value="Actif">Actif</option>';
                                            echo '<option value="Blessé">Blessé</option>';
                                            echo '<option value="Suspendu">Suspendu</option>';
                                            echo '<option value="Absent">Absent</option>';
                                            break;
                                        case 'Actif':
                                            echo '<option value="null">Non Renseigné</option>';
                                            echo '<option value="Actif" selected>Actif</option>';
                                            echo '<option value="Blessé">Blessé</option>';
                                            echo '<option value="Suspendu">Suspendu</option>';
                                            echo '<option value="Absent">Absent</option>';
                                            break;
                                        case 'Blessé':
                                            echo '<option value="null">Non Renseigné</option>';
                                            echo '<option value="Actif">Actif</option>';
                                            echo '<option value="Blessé" selected>Blessé</option>';
                                            echo '<option value="Suspendu">Suspendu</option>';
                                            echo '<option value="Absent">Absent</option>';
                                            break;
                                        case 'Suspendu':
                                            echo '<option value="null">Non Renseigné</option>';
                                            echo '<option value="Actif">Actif</option>';
                                            echo '<option value="Blessé">Blessé</option>';
                                            echo '<option value="Suspendu" selected>Suspendu</option>';
                                            echo '<option value="Absent">Absent</option>';
                                            break;
                                        case 'Absent':
                                            echo '<option value="null">Non Renseigné</option>';
                                            echo '<option value="Actif">Actif</option>';
                                            echo '<option value="Blessé">Blessé</option>';
                                            echo '<option value="Suspendu">Suspendu</option>';
                                            echo '<option value="Absent" selected>Absent</option>';
                                            break;
                                    }

                                    ?>
                                    >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase  tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-comment">
                                Commentaire
                            </label>
                            <input class="w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-comment" name="comment" type="text" <?php
                                                                                                                                                                                                                                                if ($comment == null) {
                                                                                                                                                                                                                                                    echo 'placeholder="Ajouter un commentaire"';
                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                    echo 'value="' . $comment . '"';
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                ?>>
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <button class="bg-red-600 hover:bg-red-400 text-white font-bold py-3 px-6 rounded ml-1 mr-4" name="return">
                            Retour
                        </button>
                        <button class="bg-purple-800 hover:bg-purple-500 text-white font-bold py-3 px-6 rounded ml-4" name="edit">
                            Modifier
                        </button>

                    </div>
                    <div class="flex items-center justify-center ">
                        <span class="pt-5"><?php echo $msg_error; ?> </span>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>

</html>