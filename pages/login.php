<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - United Chasetag</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>

<body>
    <main class="w-screen h-screen flex items-center justify-center">
        <section class="flex w-[800px] h-96 border-slate-200 border-[1px] rounded">
            <div class="flex flex-col items-center text-center justify-center w-1/2 h-full bg-gradient-to-br from-violet-700 to-violet-900">
                <img class="w-44" src="../img/team-logo.png" alt="Logo United">
                <span class="text-white text-3xl">United Chasetag</span>
            </div>
            <form action="login.php" method="POST" class="flex flex-col items-center justify-center w-full h-full pt-10">
                    <h1 class="text-4xl font-medium">Se connecter</h1>
                <div class="flex flex-col items-center justify-center w-full h-full">
                    <div class="flex flex-col">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="border-slate-200 border-[1px] rounded p-2 w-60">
                    </div>
                    <div class="flex flex-col pt-4">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" class="border-slate-200 border-[1px] rounded p-2 w-60">
                    </div>
                    <div class="flex flex-col pt-10">
                        <button type="submit" class="w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800 text-white text-lg">Se connecter</button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>

</html>