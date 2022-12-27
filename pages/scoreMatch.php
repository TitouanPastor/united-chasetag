<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Renseigner le score d'un match - U N I T E D</title>
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

    // On inclut les fichiers nécessaires
    require_once('match.php');
    $match = new Matchs();
    require_once('player.php');
    $player = new Player();
    require_once('statistics.php');
    $statistics = new Stats();
    $msg_error = "";

    $matchInfos = $match->getMatchInfos($idMatch);

    //On récupère les informations du match
    $p = $match->getMatch($idMatch);
    while ($data = $p->fetch()) {
        $date = $data['date_match'];
        $hour = $data['heure_match'];
        $opponents = $data['nom_eq_adv'];
        $location = $data['lieu'];
        $score_team = $data['score_equipe'];
        $score_adv = $data['score_adv'];
    }

    // Cas ou l'utilisateur clique sur le bouton "Modifier"
    if (isset($_POST["edit"])) {
        // On vériifie que les champs ne sont pas vides
        if (($_POST['score_team']) != null || ($_POST['score_adv']) != null) {
            $match->editMatch($idMatch, $date, $hour, $opponents, $location, $_POST['score_team'], $_POST['score_adv']);
            // Si le statut du match n'est pas encore en "Terminé", on le passe en "Terminé"
            if ($match->getMatchStatus($idMatch) != 1) {
                $match->matchToFinished($idMatch);
                // Si United a gagné, on ajoute un match gagné
                if ($_POST['score_team'] > $_POST['score_adv']) {
                    $statistics->updateStats(true);
                } else {
                    $statistics->updateStats(false);
                }
            }
            $match->matchToFinished($idMatch);
            header('Location: displayMatchs.php');
        } else {
            $msg_error = "Veuillez renseigner le score de l'équipe et de l'adversaire";
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
            <li class="pl-4 py-2 flex gap-2 items-center bg-violet-700 cursor-pointer border-l-2 font-medium"><i class="flex fi fi-rr-trophy"></i><a href="displayMatchs.php" class="inline-flex w-full">Matchs</a></li>
            <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-add-document"></i><a href="addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
        </ul>
        <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
            <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
        </div>
    </nav>

    <!-- Contenu de la page -->
    <main class="grid place-items-center ml-72 mr-12">
        <h2 class="m-5 text-4xl font-bold text-center">Score du match</h2>
        <h4 class="text-2xl font-medium text-center pb-4"> <?php echo $matchInfos ?> </h4>
        <form action="scoreMatch.php?id=<?php echo $_GET['id'] ?>" method="POST" class="flex flex-col gap-4 my-6 p-9 border-2 border-purple-800 rounded">
            <div class="flex">
                <div class="w-full">
                    <div class="flex flex-col gap-2">
                        <label for="score_team" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mt-2">Score de l'équipe</label>
                        <input type="number" name="score_team" id="score_team" value="<?php echo $score_team; ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" placeholder="Score United" min="0">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="score_adv" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mt-2">Score de l'adversaire</label>
                        <input type="number" name="score_adv" id="score_adv" value="<?php echo $score_adv; ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" placeholder="Score Adversaire" min="0">
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-red-600 hover:bg-red-400 text-white font-bold py-3 px-6 rounded ml-1 mr-4" name="return">
                    Retour
                </button>
                <button class="bg-purple-800 hover:bg-purple-500 text-white font-bold py-3 px-6 rounded ml-4" name="edit">
                    Ajouter le résultat
                </button>
            </div>
            <div class="flex items-center justify-center">
                <span><?php echo $msg_error; ?> </span>
            </div>
        </form>
    </main>
</body>

</html>