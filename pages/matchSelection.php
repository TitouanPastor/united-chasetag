<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Ajouter une selection - U N I T E D</title>
</head>

<body>

    <?php

    // On démarre la session
    session_start();

    // On vérifie si la personne est connectée, sinon on la redirige vers la page de connexion
    if ($_SESSION['email'] == '') {
        header('Location: login.php');
    }


    $msg_error = "";

    // On décrypte l'id du match pour le récuperer en clair
    $idMatch = $_GET['id'];
    // On récupère les informations du match
    require_once('player.php');
    $player = new Player();
    require_once('match.php');
    $match = new Matchs();
    $matchInfos = $match->getMatchInfos($idMatch);


    if (isset($_POST["add"])) {
        if (empty($_POST['playerlicense']) || sizeof($_POST['playerlicense']) < 3) {
            $msg_error .= "Veuillez sélectionner moins au trois joueurs";
        } else {
            foreach ($_POST['playerlicense'] as $playerlicense) {
                $idPlayer = $player->getIdPlayer($playerlicense);
                $match->addMatchPlayer($idMatch, $idPlayer, $_POST[$playerlicense]);
            }
            header('Location: displayMatchs.php');
        }
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
            <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-trophy"></i><a href="displayMatchs.php" class="inline-flex w-full">Matchs</a></li>
            <li class="pl-4 py-2 flex gap-2 items-center bg-violet-700 cursor-pointer border-l-2 font-medium"><i class="flex fi fi-rr-add-document"></i><a href="addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
        </ul>
        <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
            <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
        </div>
    </nav>
    <main>

        <section class="grid place-items-center ml-72 mr-12">
            <h2 class="m-5 text-3xl font-bold text-center">Selection</h2>
            <h4 class="text-2xl font-medium text-center pb-4"> <?php echo $matchInfos ?> </h4>
            <form action="matchSelection.php?id=<?php echo $_GET['id'] ?>" method="POST" class="flex flex-col justify-center gap-6">
                <ul class="px-6 h-[75vh] divide-y divide-gray-200 dark:divide-gray-700 overflow-scroll overflow-x-hidden">
                    <?php
                    require_once('player.php');
                    $player = new Player();
                    echo $player->displayPlayersSelection();
                    ?>
                </ul>
                <div class="flex items-center justify-center">
                    <button class="bg-red-600 hover:bg-red-400 text-white font-bold py-3 px-6 rounded ml-1 mr-4" name="return">
                        Retour
                    </button>
                    <button class="bg-purple-800 hover:bg-purple-500 text-white font-bold py-3 px-6 rounded ml-4" name="add">
                        Ajouter
                    </button>
                </div>
                <div class="flex items-center justify-center ">
                    <span><?php echo $msg_error; ?> </span>
                </div>
            </form>
        </section>

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
    </main>

</html>