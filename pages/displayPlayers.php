<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <title>Listes des joueurs</title>
</head>

<body>
    <!-- Navbar latérale -->
    <nav class="flex flex-col justify-between w-60 h-screen fixed bg-gradient-to-br from-violet-700 to-violet-900 text-white border-slate-500 border-r-[1px]">
            <div class="flex items-center">
                <img class="w-24" src="img/team-logo.png" alt="Logo United">
                <span class="text-2xl">United Chasetag</span>
            </div>
            <ul class="flex justify-start h-full p-4 pt-32 flex-col leading-10 text-lg">
                <li><a href="pages/members.php" class="hover:underline inline-flex w-full">Effectif</a></li>
                <li><a href="addPlayer.php" class="hover:underline inline-flex w-full">Ajouter un joueur</a></li>
                <li><a href="pages/matchs.php" class="hover:underline inline-flex w-full">Matchs</a></li>
                <li><a href="pages/addMatch.php" class="hover:underline inline-flex w-full">Ajouter un match</a></li>
            </ul>
            <div class="flex flex-col p-4">
                <a href="pages/login.php" class="w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800">Se déconnecter</a>
            </div>
        </nav>
    <main>

    <section class="grid place-items-center ml-72 mr-12">
    <h2 class="m-5 text-3xl font-bold text-center">Les joueurs</h2>
        <ul class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <?php
                require_once('player.php');
                $player = new Player();
                echo $player->displayPlayers();
            ?>
        </ul>
    </section>


</html>