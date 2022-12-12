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
        <section class="addPlayer-contains">
            <span class="box-decoration-slice bg-gradient-to-r from-indigo-600 to-pink-500 text-white px-2">Ajouter un joueur</span>
            <form action="addPlayer.php" method="post">
                <div class="p-10">
                    <div class="pb-3">
                        <label for="name">Nom</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nom du joueur">
                    </div>
                    <div class="pb-3">
                        <label for="surname">Prénom</label>
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="Prénom du joueur">
                    </div>
                    <div class="pb-3">
                        <label for="image=">Votre photo</label>
                        <input type="img" name="image" id="image" class="form-control" placeholder="Lien de votre photo">
                    </div>
                    <div class="pb-3">
                        <label for="licence">Numéro de licence</label>
                        <input type="text" name="licence" id="licence" class="form-control" placeholder="Numéro de licence">
                    </div>
                    <div class="pb-3">
                        <label for="birthday-date">Date de naissance</label>
                        <input type="date" name="birthday-date" id="birthday-date" class="form-control" placeholder="Date de naissance">
                    </div>
                    <div class="pb-3">
                        <label for="height">Taille</label>
                        <input type="text" name="height" id="height" class="form-control" placeholder="Taille du joueur">
                    </div>
                    <div class="pb-3">
                        <label for="weight">Poids</label>
                        <input type="text" name="weight" id="weight" class="form-control" placeholder="Poids du joueur">
                    </div>
                    <div class="pb-3">
                        <label for="favorite-position">Poste préféré</label>
                        <input type="text" name="favorite-position" id="favorite-position" class="form-control" placeholder="Poste préféré">
                    </div>
                    <div class="pb-3">
                        <label for="comment>">Commentaire</label>
                        <input type="text" name="comment" id="comment" class="form-control" placeholder="Commentaire">
                    </div>
                    <div class="pb-3">
                        <label for="statut =">Statut</label>
                        <input type="text" name="statut" id="statut" class="form-control" placeholder="Statut">
                    </div>
                    <div class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </div>  
        </section>
    </main>
</body>
</html>