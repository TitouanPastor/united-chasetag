<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <title>Ajouter un match - U N I T E D</title>
</head>

<body>

    <?php

    // Déclaration des variables de persistence en cas d'erreur ou de rafraichissement de la page
    $date = "";
    $hour = "";
    $opponents = "";
    $location = "";

    if (isset($_POST["add"])) {
        $date = $_POST["date"];
        $hour = $_POST["hour"];
        $opponents = $_POST["opponents"];
        $location = $_POST["location"];
    }

    // Restriction de la date de saisie à la date du jour + 1 semaine
    // Obtenir la date du jour
    $today = new DateTime();

    // Ajouter une semaine à la date du jour
    $minDate = $today->add(new DateInterval('P1W'));

    // Formater la date minimale au format Y-m-d (année-mois-jour)
    $minDate = $minDate->format('Y-m-d');

    // Traitements de différents cas utilisateur
    require_once('match.php');
    $match = new Matchs();
    $msg_error = "";
    if (isset($_POST["add"])) {
        //Tout les champs sont remplis
        if (!empty($_POST["date"]) && !empty($_POST["hour"]) && !empty($_POST["opponents"]) && !empty($_POST["location"])) {
            //On vérifie que le match n'existe pas déjà
            if ($match->matchExist($_POST["date"], $_POST["hour"])) {
                $msg_error = "Le match existe déjà";
            } else {
                //Le match n'existe pas déjà
                //On ajoute le match
                $match->addMatch($_POST["date"], $_POST["hour"], $_POST["opponents"], $_POST["location"]);
                $msg_error = "Le match a bien été ajouté";
                $idMatch = $match->getIdMatch($_POST["date"], $_POST["hour"]);
                //On crypte l'id du match pour le passer en paramètre dans l'url
                $idMatchencode = openssl_encrypt($idMatch, 'AES-256-CBC', 'titi');
                header("Location: matchSelection.php?id=$idMatchencode");
            }
        } else {
            $msg_error = "Tous les champs doivent être remplis";
        }
    }

    ?>

    <main>
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
                <a href="pages/login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
            </div>
        </nav>

        <section class="h-screen flex items-center justify-center mx-10">
            <div class="my-6 px-9  border-2 border-purple-800 rounded ">
                <h2 class="m-5 text-4xl font-bold text-center">Ajouter un tournoi</h2>
                <form class="block w-full max-w-lg mb-10" action="addMatch.php" method="post">

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-date">
                                Date
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-date" name="date" type="date" min="<?php echo $minDate; ?>" value="<?php echo $date ?>">
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-hour">
                                Heure
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-hour" name="hour" type="time"  value="<?php echo $hour ?>">
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase  tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-opponents">
                                Équipe adverse
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-opponents" name="opponents" type="text" placeholder="BlackList"  value="<?php echo $opponents ?>">
                        </div>

                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-location">
                                Lieu
                            </label>
                            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-location" name="location" type="text" placeholder="Toulouse Arena"  value="<?php echo $location ?>">
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