<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - U N I T E D</title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
</head>

<body>

    <?php
    // On démarre la session
    session_start();

    // On détruit les variables de notre session (déconnection de l'utilisateur)
    $_SESSION['email'] = '';

    // On initialise les variables
    $info_login = '';

    // Traitement du formulaire
    if (isset($_POST['submit'])) {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            if ($_POST['email'] == 'chasetag@gmail.com' && $_POST['password'] == 'chasetag') {
                $_SESSION['email'] = $_POST['email'];
                header('Location: ../index.php');
            } else {
                $info_login = 'Identifiants incorrects';
            }
        } else {
            $info_login = 'Veuillez remplir tous les champs';
        }
    }

    ?>

    <main class="w-screen h-screen flex items-center justify-center">
        <section class="flex w-[800px] h-96 border-2 border-purple-800 rounded">
            <div class="flex flex-col items-center text-center justify-center w-1/2 h-full bg-gradient-to-br from-violet-700 to-violet-900 scale-[100.5%]">
                <img class="w-44" src="../img/team-logo.png" alt="Logo United">
                <span class="text-white text-3xl">United Chasetag</span>
            </div>
            <form action="login.php" method="POST" class="flex flex-col items-center justify-center w-full h-full pt-10">
                <h1 class="text-4xl font-bold">Connexion</h1>
                <div class="flex flex-col items-center justify-center w-full h-full">
                    <div class="flex flex-col">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" id="grid-first-name" type="text" placeholder="Email">
                    </div>
                    <div class="flex flex-col pt-4">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" id="grid-first-name" type="text" placeholder="Mot de passe">
                    </div>
                    <span><?php echo $info_login ?></span>
                    <div class="min-h-min flex items-center gap-2 mt-10 bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800 text-white text-lg">
                        <i class="flex fi fi-rr-sign-in-alt"></i>
                        <input type="submit" name="submit" class="w-fit" value="Se connecter">
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>

</html>