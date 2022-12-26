<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Liste des matchs - U N I T E D</title>
</head>

<body>

    <?php

    require_once('player.php');
    require_once('match.php');
    $player = new Player();
    $match = new Matchs();


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
            <a href="pages/login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
        </div>
    </nav>

    <!-- Contenu de la page -->
    <main class="grid place-items-center ml-72 mr-12">
    <h2 class="m-5 text-3xl font-bold text-center">Liste des matchs</h2>
        <section class="w-2/3 flex flex-wrap justify-center gap-12">
            <?php

            echo $match->displayAllMatchs();

            ?>
        </section>
    </main>

</body>

</html>