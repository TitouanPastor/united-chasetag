<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Ajouter un joueur - U N I T E D</title>
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
$msg_error = "";

    // Déclaration des variables de persistence en cas d'erreur ou de rafraichissement de la page
    $name = "";
    $lastname = "";
    $picture = "";
    $license= "";
    $birthday="";
    $weight="60";
    $size="170";
    $position="Chat";

    if (isset($_POST["add"])) {
        $name = $_POST["name"];
        $lastname = $_POST["lastname"];
        $picture = $_FILES["picture"];
        $license = $_POST["license"];
        $birthday = $_POST["birthday"];
        $weight = $_POST["weight"];
        $size = $_POST["size"];
        $position = $_POST["position"];
    }   

if (isset($_POST["add"])) {
    //Tout les champs sont remplis
    if (!empty($_POST['name']) && !empty($_POST['lastname']) && !empty($_FILES['picture']) && !empty($_POST['license']) && !empty($_POST['birthday']) && !empty($_POST['weight']) && !empty($_POST['size']) && !empty($_POST['position'])) {
        //Format de la licence incorrect (00000AA)
        if (preg_match('/^[0-9]{5}[A-Z]{2}$/', $_POST['license'])) {
            //Date supérieur à 18 ans
            if (date_diff(date_create($_POST['birthday']), date_create('today'))->y >= 18) {
                //Joueur deja existant 
                if (!$player->playerExist($_POST['license'], $_POST['name'], $_POST['lastname'])) {
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
                                        $picture = $fileNameNew;
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
                        
                    $player->addPlayer($_POST['name'], $_POST['lastname'], $picture, $_POST['license'], $_POST['birthday'], $_POST['weight'], $_POST['size'], $_POST['position']);
                    $msg_error = "Joueur ajouté";
                }else{
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
if(isset($_POST["return"])){
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
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-users-alt"></i></i><a href="displayPlayers.php" class="inline-flex w-full">Effectif</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center bg-violet-700 cursor-pointer border-l-2 font-medium"><i class="flex fi fi-rr-user-add"></i><a href="addPlayer.php" class="inline-flex w-full">Ajouter un joueur</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-trophy"></i><a href="displayMatchs.php" class="inline-flex w-full">Matchs</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-add-document"></i><a href="addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-stats"></i><a href="displayStatistics.php" class="inline-flex w-full">Statistiques</a></li>
            </ul>
            <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
                <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
            </div>
        </nav>

        <section class="h-screen flex items-center justify-center mx-10">
            <div class="my-6 px-9  border-2 border-purple-800 rounded ">
                <h2 class="m-5 text-4xl font-bold text-center">Ajouter un joueur</h2>
                <form class="block w-full max-w-lg mb-10" action="addPlayer.php" method="post" enctype="multipart/form-data">

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                Nom
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" id="grid-first-name" name="name" type="text" placeholder="Votre nom" value="<?php echo $lastname; ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Prénom
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-last-name" name="lastname" type="text" placeholder="Votre prénom" value="<?php echo $name; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase  tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-picture">
                                Photo
                            </label>
                            <input class="w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-picture" name="picture" type="file" placeholder="Lien vers votre photo" accept="image/png, image/jpeg" value="<?php echo $picture; ?>">
                        </div>

                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-license-number">
                                Numéro de licence
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-license-number" name="license" type="text" placeholder="Format : (00000AA)" value="<?php echo $license; ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-birthday">
                                Date de naissance
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-birthday" name="birthday" type="date" value="<?php echo $birthday; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3    ">
                            <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-weight">
                                POIDS (en KG)
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-weight" name="weight" type="number" min="40" max="150" value="<?php echo $weight; ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-size">
                                Taille (en cm)
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-size" name="size" type="number" min="130" max="22s0" value="<?php echo $size; ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">


                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-favorite_position">
                                Poste préféré
                            </label>
                            <div class="relative">
                                <select class="block w-full bg-gray-200 border  text-gray-700 border-purple-800 rounded  py-3 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-favorite_position" name="position" value="<?php echo $position; ?>">
                                    <option value="Chat">Chat</option>
                                    <option value="Souris">Souris</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <button class="bg-red-600 hover:bg-red-400 text-white font-bold py-3 px-6 rounded ml-1 mr-4" name="return">
                            Retour
                        </button>
                        <button class="bg-purple-800 hover:bg-purple-500 text-white font-bold py-3 px-6 rounded ml-4" name="add">
                            Ajouter
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