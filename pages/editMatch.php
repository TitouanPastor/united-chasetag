<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Modifier un match - U N I T E D</title>
</head>

<body>

    <?php

    // On démarre la session
    session_start();

    // On vérifie si la personne est connectée, sinon on la redirige vers la page de connexion
    if ($_SESSION['email'] == '') {
        header('Location: login.php');
    }


    //On decrypte l'id du match pour récupérer l'id du match
    $idMatch = base64_decode($_GET['id']);
    $idMatch = openssl_decrypt($idMatch, "aes-256-ecb", "toto");

    // Restriction de la date de saisie à la date du jour + 1 semaine
    // Obtenir la date du jour
    // $today = new DateTime();

    // Ajouter une semaine à la date du jour
    // $minDate = $today->add(new DateInterval('P1W'));

    // Formater la date minimale au format Y-m-d (année-mois-jour)
    // $minDate = $minDate->format('Y-m-d');

    // On inclut les fichiers nécessaires
    require_once('match.php');
    $match = new Matchs();
    require_once('player.php');
    $player = new Player();
    $msg_error = "";

    //On récupère les informations du match
    $p = $match->getMatch($idMatch);
    while ($data = $p->fetch()) {
        $date = $data['date_match'];
        $hour = $data['heure_match'];
        $opponents = $data['nom_eq_adv'];
        $location = $data['lieu'];
    }
    $hour = substr($hour, 0, 5);

    // Cas ou l'utilisateur clique sur le bouton "Modifier"
    if (isset($_POST["edit"])) {
        // On vériifie que les champs ne sont pas vides
        if (empty($_POST['date']) || empty($_POST['hour']) || empty($_POST['opponents']) || empty($_POST['location'])) {
            $msg_error .= "Veuillez remplir tous les champs";
        } else {
            // On rentre les infos sur le match
            $match->editMatch($idMatch, $_POST['date'], $_POST['hour'], $_POST['opponents'], $_POST['location'], null, null);
            // On s'occupe des joueurs
            if (sizeof($_POST['playerlicense']) < 3) {
                $msg_error .= "Veuillez sélectionner au moins trois joueurs";
            } else {
                // On met à jour les infos sur les jooeurs
                $match->dropMatchAllPlayers($idMatch);
                foreach ($_POST['playerlicense'] as $playerlicense) {
                    $idPlayer = $player->getIdPlayer($playerlicense);
                    $match->addMatchPlayer($idMatch, $idPlayer, $_POST[$playerlicense]);
                    header('Location: displayMatchs.php');
                }
            }
        }
    }

    if(isset($_POST["return"])){
        header("location: displayMatchs.php"); 
    }

    ?>

    <!-- Navbar latérale -->
    <nav class="flex flex-col justify-between w-60 h-screen fixed bg-gradient-to-br from-violet-700 to-violet-900 text-white border-slate-500 border-r-[1px]">
        <div class="mx-4 flex items-center border-b border-purple-50 border-opacity-25">
            <img class="w-24" src="../img/team-logo.png" alt="Logo United">
            <span class="text-2xl">United Chasetag</span>
        </div>
        <ul class="flex justify-start h-full pt-32 flex-col leading-10 text-lg">
            <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-users-alt"></i></i><a href="displayPlayers.php" class="inline-flex w-full">Effectif</a></li>
            <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-user-add"></i><a href="addPlayer.php" class="inline-flex w-full">Ajouter un joueur</a></li>
            <li class="pl-4 py-2 flex gap-2 items-center bg-violet-700 cursor-pointer border-l-2 font-medium"><i class="flex fi fi-rr-trophy"></i><a href="displayMatchs.php" class="inline-flex w-full">Matchs</a></li>
            <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-add-document"></i><a href="addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
        </ul>
        <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
            <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
        </div>
    </nav>

    <!-- Contenu de la page -->
    <main class="grid place-items-center ml-72 mr-12">
        <h2 class="m-5 text-4xl font-bold text-center">Modifier un match</h2>
        <form action="editMatch.php?id=<?php echo $_GET['id'] ?>" method="POST" class="flex flex-col min-w-[1200px] gap-4 my-6 p-9 border-2 border-purple-800 rounded">
            <div class="flex">
                <div class="w-1/3 border-r-2 border-purple-800 pr-6">
                    <div class="flex flex-col gap-2">
                        <label for="date" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mt-2">Date du match</label>
                        <input type="date" name="date" id="date" min="<?php  if ($minDate != "") echo $minDate; ?>" value="<?php echo $date; ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="hour" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mt-2">Heure du match</label>
                        <input type="time" name="hour" id="hour" value="<?php echo $hour; ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="opponents" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mt-2">Adversaire</label>
                        <input type="text" name="opponents" id="opponents" value="<?php echo $opponents; ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="location" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mt-2">Lieu du match</label>
                        <input type="text" name="location" id="location" value="<?php echo $location; ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800">
                    </div>
                </div>
                <div class="w-2/3">
                    <ul class="px-6 h-[60vh] divide-y divide-gray-200 dark:divide-gray-700 overflow-scroll overflow-x-hidden">
                        <?php
                        require_once('player.php');
                        $player = new Player();
                        echo $player->displayPlayersForExistingMatch($idMatch);
                        ?>
                    </ul>
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
                <span><?php echo $msg_error; ?> </span>
            </div>
        </form>
    </main>
    <script>
        // Fonction pour décocher les autres radio buttons
        function toggleRadiosSelection(licence) {
            var radios = document.getElementsByName(licence);
            console.log(radios);
            for (var i = 0; i < radios.length; i++) {
                if (radios[i].disabled == true) {
                    radios[0].checked = true;
                    radios[i].disabled = false;
                } else {
                    radios[i].disabled = true;
                    radios[i].checked = false;
                }
            }
        }
    </script>
</body>

</html>