<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <title>Statistiques - U N I T E D</title>
</head>
<?php
require_once('statistics.php');
$stats = new Stats();
$matchs = $stats->getMatchs();
$positions = $stats->getPositions();
// On démarre la session
session_start();

// On vérifie si la personne est connectée, sinon on la redirige vers la page de connexion
if ($_SESSION['email'] == '') {
    header('Location: login.php');
}
?>

<!-- Navbar latérale -->
<nav class="flex flex-col justify-between w-60 h-screen fixed bg-gradient-to-br from-violet-700 to-violet-900 text-white border-slate-500 border-r-[1px]">
    <div class="mx-4 flex items-center border-b border-purple-50 border-opacity-25">
        <img class="w-24" src="../img/team-logo.png" alt="Logo United">
        <span class="text-2xl">United Chasetag</span>
    </div>
    <ul class="flex justify-start h-full pt-32 flex-col leading-10 text-lg">
        <li class="pl-4 py-2 flex gap-2 items-center bg-violet-700 cursor-pointer border-l-2 font-medium"><i class="flex fi fi-rr-users-alt"></i></i><a href="displayPlayers.php" class="inline-flex w-full">Effectif</a></li>
        <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-user-add"></i><a href="addPlayer.php" class="inline-flex w-full">Ajouter un joueur</a></li>
        <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-trophy"></i><a href="displayMatchs.php" class="inline-flex w-full">Matchs</a></li>
        <li class="pl-4 py-2 flex gap-2 items-center hover:bg-violet-700 cursor-pointer hover:border-l-2"><i class="flex fi fi-rr-add-document"></i><a href="addMatch.php" class="inline-flex w-full">Ajouter un match</a></li>
    </ul>
    <div class="mx-4 flex items-center justify-center p-4 border-t border-purple-50 border-opacity-25">
        <a href="login.php" class="flex items-center gap-2 w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800"><i class="flex fi fi-rr-exit"></i>Se déconnecter</a>
    </div>
</nav>

<body>
    <section class="flex items-center justify-center mx-10">
        <h2 class="m-5 text-3xl font-bold text-center">Statistiques</h2>
    </section>
    <section class="flex flex-col items-center justify-center mx-10">
        <h3 class="m-5 text-2xl font-semibold text-center">Matchs</h3>

        <div class="overflow-hidden">
            <table class="w-full mx-10">
                <thead class="border-b my-5">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Matchs joués
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Matchs gagnés
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Matchs perdus
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                            Matchs nuls
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?php echo $matchs[0] ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?php echo $matchs[1] . '%' ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?php echo $matchs[2] . '%' ?>
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <?php echo $matchs[3] . '%' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <section class="grid place-items-center ml-72 mr-12">
        <h3 class="m-5 text-2xl font-semibold text-center">Matchs</h3>
        <ul class="w-full divide-y divide-gray-200 dark:divide-gray-700">
        <?php
        foreach ($positions as $joueur => $position) {
            echo '<li class="pb-3 sm:pb-4" >
            <div class="flex items-center space-x-4 my-4">
                <div class="flex-shrink-0">
                    <img class="w-16 h-16 rounded-full" src="' . $position['picture']. '" alt="Photo de ' . $joueur.'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate ">
                        [' . $position['license'] . '] - ' . $joueur . '
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        Titulaire : ' . $position['nb_owner'] . ' Remplacant : ' . $position['nb_alternate'] . '
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400 whitespace-normal   ">
                     ' . $position['fav_position'] . ' - ' . $position['state'] . '
                    </p>
                
                </div>
            </div>
        </li>';
        }


        ?>
        </ul>
    </section>
</body>

</html>