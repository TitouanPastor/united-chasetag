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
                <img class="w-32" src="../img/team-logo.png" alt="Logo United">
                <span class="text-white text-3xl">United Chasetag</span>
            </div>
            <div>
                <form action="" class="w-full h-full">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex flex-col w-1/2">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="border-slate-200 border-[1px] rounded p-2">
                        </div>
                        <div class="flex flex-col w-1/2">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" class="border-slate-200 border-[1px] rounded p-2">
                        </div>
                        <div class="flex flex-col w-1/2">
                            <button type="submit" class="w-fit bg-violet-700 transition-colors p-2 rounded hover:bg-violet-800">Se connecter</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>

</html>