<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Evaluer un match - U N I T E D</title>
</head>

<body>

    <?php

    // On démarre la session
    session_start();

    // On vérifie si la personne est connectée, sinon on la redirige vers la page de connexion
    if ($_SESSION['email'] == '') {
        header('Location: login.php');
    }

    // On inclut les fichiers nécessaires
    require_once('player.php');
    require_once('match.php');
    $player = new Player();
    $match = new Matchs();
    $msg_error = "";

    // On décrypte l'ID du match
    $idMatch = base64_decode($_GET['id']);
    $idMatch = openssl_decrypt($idMatch, "aes-256-ecb", "toto");

    // On récupère les informations du match
    $matchInfos = $match->getMatchInfos($idMatch);

    // Cas ou l'utilisateur clique sur le bouton Ajouter
    if (isset($_POST['add'])) {
        $tab_player_name = $player->getPlayerNameArrayFromMatch($idMatch);
        while ($player_tab = $tab_player_name->fetch()) {
            $player_name = $player_tab['nom'];
            $player_license = $player_tab['numero_de_licence'];
            $player_id = $player->getIdPlayer($player_license);
            // On ajoute la note du joueur
            $player->addPlayerRating($idMatch,$player_id, $_POST['rating-'.$player_name]);
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
        <h2 class="m-5 text-4xl font-bold text-center">Evaluer les joueurs</h2>
        <h4 class="text-2xl font-medium text-center pb-4"> <?php echo $matchInfos ?> </h4>
        </div>
        <form action="evaluateMatch.php?id=<?php echo $_GET['id'] ?>" method="post">
            <ul class="p-4">
                <?php

                // On récupère les informations du match
                echo $player->displayPlayersForEvaluation($idMatch);

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
                <span class="pt-5"><?php echo $msg_error; ?> </span>
            </div>
        </form>
    </main>

    <script>
        function labelClicked(label) {

            // On récupère l'id du label
            var id = label.id;
            // On récupère la première class du label
            var classLabel = label.classList[0];
            // On récupère les labels précédents
            var labels = document.querySelectorAll('.' + classLabel);
            // On parcourt les labels précédents
            for (var i = 0; i < labels.length; i++) {
                // Si l'id du label précédent est inférieur à l'id du label cliqué
                if (labels[i].id <= id) {
                    // On ajoute la classe "text-yellow-700" à l'input
                    labels[i].classList.add("text-yellow-500");
                    labels[i].classList.remove("text-gray-300");
                } else {
                    // On ajoute la classe "text-gray-300" à l'input
                    labels[i].classList.add("text-gray-300");
                    labels[i].classList.remove("text-yellow-500");
                }
            }
        }
    </script>
</body>

</html>