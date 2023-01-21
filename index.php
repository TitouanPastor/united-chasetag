<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
</head>

<body>

    <?php

    // On démarre la session
    session_start();

    // On vérifie si la personne est connectée, sinon on la redirige vers la page de connexion
    if ($_SESSION['email'] == '') {
        header('Location: pages/login.php');
    }

    ?>

    <main class="flex">

        <!-- Navbar latérale -->
        <nav class="flex flex-col justify-between w-60 h-screen fixed bg-gradient-to-br from-violet-700 to-violet-900 text-white border-slate-500 border-r-[1px]">
            <div class="mx-4 flex items-center border-b border-purple-50 border-opacity-25">
                <img class="w-24" src="img/team-logo.png" alt="Logo United">
                <span class="text-2xl">United Chasetag</span>
            </div>
            <ul class="flex justify-start h-full pt-32 flex-col leading-10 text-lg">
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-users-alt"></i></i><a href="pages/displayPlayers.php" class="inline-flex w-full">Effectif</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-user-add"></i><a href="pages/addPlayer.php" class="inline-flex w-full">Ajouter un joueur</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-trophy"></i><a href="pages/matchs.php" class="inline-flex w-full">Matchs</a></li>
                <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-add-document"></i><a href="pages/addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
            </ul>
            <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
                <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
            </div>
        </nav>

        <!-- Contenu -->


        <section class="flex w-full h-screen bg-repeat-space bg-[url('../img/team-logo-black.png')] ">
            <div class=" m-auto">
            <span class="p-52 rounded-md text-center text-black text-4xl"> ➡️ Bienvenue sur l'application United Chasetag !</span>
            </div>
                
            
        </section>
    </main>
</body>

</html>