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

        <section class="flex w-full h-[100vh] place-items-center ml-72 mr-12">
            <div class="w-full h-full flex-col flex justify-center gap-32 items-center p-10">
                <div class="flex flex-col gap-4">
                    <h1 class="text-5xl text-center font-semibold">Bienvenue sur United Chasetag</h1>
                    <p class="text-2xl text-center">Vous pouvez dès à présent accéder au panel administrateur disponible à votre gauche.</p>
                </div>
                <div class="flex justify-center items-center gap-8">
                    <img class="w-44" src="img/team-logo-black.png" alt="Logo United">
                    <div class="flex flex-col gap-2 w-96">
                        <h2 class="font-semibold text-xl">Le Chasetag</h2>
                        <p>En compétition, les équipes s’affrontent sur une série de manches en un contre un, avec un système de points. Tout se joue sur un terrain de 12 mètres par 12 mètres, avec un parcours d’obstacles à éviter. Le chat a 20 secondes pour attraper la souris. S’il y parvient, il devient à son tour la souris. S’il échoue, l’adversaire qui a réussi à s’échapper marque un point.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>