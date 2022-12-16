<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <title>Ajouter un joueur</title>
</head>

<body>
    <main>
        <section class="addPlayer-contains grid h-screen place-items-center">
            <h2 class="text-4xl font-bold dark:text-dark h-0">Ajouter un joueur</h2>
            <form class="w-full max-w-lg">
                
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                            Nom
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-black-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white border-purple-800" id="grid-first-name" type="text" placeholder="Votre nom">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                            Prénom
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-last-name" type="text" placeholder="Votre prénom">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3">
                        <label class="block uppercase  tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-picture">
                            Photo
                        </label>
                        <input class="w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-picture" type="file" placeholder="Lien vers votre photo" accept="image/png, image/jpeg">
                    </div>
       
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-license-number">
                            Numéro de licence
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-license-number" type="text" placeholder="Format : (00000AA)">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-birthday">
                            Date de naissance
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 " id="grid-birthday" type="date" >
                    </div>  
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3    ">
                        <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-weight">
                            POIDS (en KG)
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-weight" type="number" min="40" max="150"value="60" >
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-size">
                            Taille (en cm)
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-purple-800 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-size" type="number" min="130" max="22s0"value="170" >
                    </div>  
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-red-600 hover:bg-red-400 text-white font-bold py-2 px-4 rounded mr-4">
                        Retour
                    </button>
                    <button class="bg-purple-800 hover:bg-purple-500 text-white font-bold py-2 px-4 rounded ml-4">
                        Ajouter
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>