<?php

class Player
{
    private $sql;
    public $IMG_DIR = '../imgPlayers/';

    public function __construct()
    {
        //Connexion à la base de données
        require_once('../BDD.php');
        $this->sql = new connectBDD();
    }

    //Requete pour ajouter un joueur dans la base de données 
    public function addPlayer($name, $lastname, $picture_path, $lience, $birthday, $weight, $size, $position)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('INSERT INTO Joueur  VALUES (null, :name, :lastname, :picture_path, :lience, :birthday, :size, :weight, :position, null, null)');
            $req->execute(array(
                'name' => $name,
                'lastname' => $lastname,
                'picture_path' => $picture_path,
                'lience' => $lience,
                'birthday' => $birthday,
                'weight' => $weight,
                'size' => $size,
                'position' => $position
            ));
        }catch(Exception $e){
            echo('Erreur lors de l\'ajout d\'un joueur ' . $e->getMessage());
        }
    }

    //Retourne true si le joueur existe déjà dans la base de données false sinon
    public function playerExist($licence, $name, $lastname)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur WHERE numero_de_licence = :licence OR (upper(nom )= upper(:name) AND upper(prenom) = upper(:lastname))');
            $req->execute(array(
                'licence' => $licence,
                'name' => $name,
                'lastname' => $lastname
            ));
            //si le resultat de la requete à plus d'une ligne, alors le joueur existe déjà
            if ($req->rowCount() > 0) {
                return true;
            }
        }catch(Exception $e){
            echo('Erreur lors de la verification de si un joueur existe deja : ' . $e->getMessage());
        }
        return false;
    }

    //Retourne un <li></li> avec les informations du joueur pour afficher dans un tableau <ul>
    public function displayAPlayer($name, $lastname, $licence, $picture, $birthday, $weight, $size, $position, $state, $comment, $id)
    {
        //Date de naissance > age
        $year = date('Y') - date('Y', strtotime($birthday));

        // Initilisation des variables null
        if ($state == null) {
            $state = "Aucun statut";
        }
        if ($comment == null) {
            $comment = "Aucun commentaire";
        }

        //Cryptage de l'id du joueur pour l'envoyer dans l'url si ont veut modifier ou supprimer le joueur
        $idPlayerEncode = openssl_encrypt($id, "aes-256-ecb", "toto");
        $idPlayerEncode = base64_encode($idPlayerEncode);

        //Ajout du chemin de l'image
        $picture = $this->IMG_DIR . $picture;

        //Code html pour afficher le joueur
        return '<li class="pb-3 sm:pb-4 " >
            <div class="flex items-center space-x-4 my-4">
                <div class="flex-shrink-0">
                    <img class="w-16 h-16 rounded-full" src="' . $picture . '" alt="Photo de ' . $lastname . ' ' . $name . '">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate ">
                        [' . $licence . '] - ' . $lastname . ' ' . $name . ' - ' . $year . ' ans - ' . $weight . ' KG - ' . $size . ' CM
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        ' . $position . ' - ' . $state . '
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400 whitespace-normal   ">
                        Commentaire : ' . $comment . '
                    </p>
                </div>
                <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                    <a href="editPlayer.php?id=' . $idPlayerEncode . '" class="p-2"> MODIFIER </a> 
                    <a title="Supprimer le joueur" class="cursor-pointer" value="displayPlayers.php?deletePlayer=' . $idPlayerEncode . '"  onclick="openPopUp(this)" class="p-2" name="delete"> SUPPRIMER </a>
                </div>
            </div>
        </li>';
    }

    //Retourne l'id du joueur
    public function getIdPlayer($licence)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT id_joueur FROM Joueur WHERE numero_de_licence = :licence');
            $req->execute(array(
                'licence' => $licence
            ));
            $id = $req->fetch();
            return $id['id_joueur'];
        }catch(Exception $e){
            echo('Erreur lors de la recuperation du joueur: ' . $e->getMessage());
        }
    }

    //Retourne les joueurs sous forme de tableau <ul>
    public function displayPlayers()
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur order by nom, prenom');
            $req->execute();
            $players = $req->fetchAll();
            $req->closeCursor();
            $this->sql->closeConnection();
            $display = "";
            foreach ($players as $player) {
                $display .= $this->displayAPlayer($player['nom'], $player['prenom'], $player['numero_de_licence'], $player['photo'], $player['date_de_naissance'], $player['poid'], $player['taille'], $player['poste_prefere'], $player['statut'], $player['commentaire'], $player['id_joueur']);
            }
            return $display;
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage des joueurs : ' . $e->getMessage());
        }
    }

    //Affichage des joueurs dans la page des matchs
    public function displayPlayerSummary($name, $surname, $picture, $birthday, $state)
    {
        $picture = $this->IMG_DIR . $picture;
        $year = date('Y') - date('Y', strtotime($birthday));
        if ($state == null) {
            $state = "Aucun statut";
        }
        return '<li class="pb-3 sm:pb-4" >
            <div class="flex items-center space-x-4 py-[3px]">
                <div class="flex-shrink-0">
                    <img class="w-8 h-8 rounded-full" src="' . $picture . '" alt="Neil image">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate ">
                        ' . $surname . ' ' . $name . ' - ' . $year . ' ans
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        ' . $state . '
                    </p>
                </div>
            </div>
        </li>';
    }

    //Affichage des joueurs pour les selectionner
    public function displayAPlayerSelection($name, $lastname, $licence, $picture, $birthday, $weight, $size, $position, $state, $comment)
    {
        //Transformer birthday en age 
        $year = date('Y') - date('Y', strtotime($birthday));
        if ($state == null) {
            $state = "Aucun statut";
        }
        if ($comment == null) {
            $comment = "Aucun commentaire";
        }

    
        $picture = $this->IMG_DIR . $picture;
        return '<li class="pb-3 sm:pb-4" >
            <div class="flex items-center space-x-4 my-4">
                <input type="checkbox" onclick="toggleRadiosSelection(\'' . $licence . '\')" class="accent-purple-800 w-6 h-6" name="playerlicense[]" value="' . $licence . '">
                <div class="flex-shrink-0">
                    <img class="w-8 h-8 rounded-full" src="' . $picture . '" alt="Neil image">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate ">
                        [' . $licence . '] - ' . $lastname . ' ' . $name . ' - ' . $year . ' ans - ' . $weight . ' KG - ' . $size . ' CM
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        ' . $position . ' - ' . $state . '
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400 whitespace-normal   ">
                        Commentaire : ' . $comment . '
                    </p>
                </div>
                <div class="flex flex-col justify-between text-base font-semibold text-gray-900 ">
                    <div class="flex gap-2 text-base font-semibold text-gray-900 ">
                        <input class="accent-purple-800" type="radio" name=' . $licence . ' value="titulaire" disabled>
                        <label for="titulaire">Titulaire</label>
                    </div>
                    <div class="flex gap-2 text-base font-semibold text-gray-900 ">
                        <input class="accent-purple-800" type="radio" name=' . $licence . ' value="remplacant" disabled>
                        <label for="remplacant">Remplacant</label>
                    </div>
                </div>
            </div>
        </li>';
    }

    //Affichage des joueurs pour les selectionner
    public function displayPlayersSelection()
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur where statut = "Actif" order by nom, prenom');
            $req->execute();
            $players = $req->fetchAll();
            $req->closeCursor();
            $this->sql->closeConnection();
            $display = "";
            foreach ($players as $player) {
                $display .= $this->displayAPlayerSelection($player['nom'], $player['prenom'], $player['numero_de_licence'], $player['photo'], $player['date_de_naissance'], $player['poid'], $player['taille'], $player['poste_prefere'], $player['statut'], $player['commentaire']);
            }
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage des joueurs : ' . $e->getMessage());
        }
        return $display;
    }

    //selection du role d'un joueur participant a un match
    public function playerIsPlayingAMatch($id_match, $id_player)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT role FROM Participer WHERE id_joueur = :id_player AND id_game = :id_match order by role');
            $req->execute(array(
                'id_player' => $id_player,
                'id_match' => $id_match
            ));
            //si le resultat de la requete à plus d'une ligne, alors le joueur existe déjà
            if ($req->rowCount() > 0) {
                $res = $req->fetch();
                return $res['role'];
            }
            return false;
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage du role du joueur : ' . $e->getMessage());
        }
    }

    public function displayAPlayerForExistingMatch($id_match, $id_player, $name, $lastname, $licence, $picture, $birthday, $weight, $size, $position, $state, $comment)
    {
        //Transformer birthday en age 
        $year = date('Y') - date('Y', strtotime($birthday));
        if ($state == null) {
            $state = "Aucun statut";
        }
        if ($comment == null) {
            $comment = "Aucun commentaire";
        }

        // On modifie les divs suivants si ils sont titulaires ou remplacants, et si ils sont selectionnés ou non
        if ($this->playerIsPlayingAMatch($id_match, $id_player) == "titulaire") {
            $div_selectionne = '<input type="checkbox" onclick="toggleRadiosSelection(\'' . $licence . '\')" class="accent-purple-800 w-6 h-6" name="playerlicense[]" value="' . $licence . '" checked>';
            $div_titulaire = '<input class="accent-purple-800" type="radio" name=' . $licence . ' value="titulaire" checked>';
            $div_remplacant = '<input class="accent-purple-800" type="radio" name=' . $licence . ' value="remplacant">';
        } else if ($this->playerIsPlayingAMatch($id_match, $id_player) == "remplacant") {
            $div_selectionne = '<input type="checkbox" onclick="toggleRadiosSelection(\'' . $licence . '\')" class="accent-purple-800 w-6 h-6" name="playerlicense[]" value="' . $licence . '" checked>';
            $div_titulaire = '<input class="accent-purple-800" type="radio" name=' . $licence . ' value="titulaire">';
            $div_remplacant = '<input class="accent-purple-800" type="radio" name=' . $licence . ' value="remplacant" checked>';
        } else {
            $div_selectionne = '<input type="checkbox" onclick="toggleRadiosSelection(\'' . $licence . '\')" class="accent-purple-800 w-6 h-6" name="playerlicense[]" value="' . $licence . '">';
            $div_titulaire = '<input class="accent-purple-800" type="radio" name=' . $licence . ' value="titulaire" disabled>';
            $div_remplacant = '<input class="accent-purple-800" type="radio" name=' . $licence . ' value="remplacant" disabled>';
        }

        $picture = $this->IMG_DIR . $picture;
        return '<li class="pb-3 sm:pb-4" >
            <div class="flex items-center space-x-4 my-4">
                ' . $div_selectionne . '
                <div class="flex-shrink-0">
                    <img class="w-8 h-8 rounded-full" src="' . $picture . '" alt="Neil image">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate ">
                        [' . $licence . '] - ' . $lastname . ' ' . $name . ' - ' . $year . '- ' . $weight . ' - ' . $size . '
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        ' . $position . ' - ' . $state . '
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400 whitespace-normal   ">
                        Commentaire : ' . $comment . '
                    </p>
                </div>
                <div class="flex flex-col justify-between text-base font-semibold text-gray-900 ">
                    <div class="flex gap-2 text-base font-semibold text-gray-900 ">
                        ' . $div_titulaire . '
                        <label for="titulaire">Titulaire</label>
                    </div>
                    <div class="flex gap-2 text-base font-semibold text-gray-900 ">
                        ' . $div_remplacant . '
                        <label for="remplacant">Remplacant</label>
                    </div>
                </div>
            </div>
        </li>';
    }

    // Affiche tous les joueurs pour un match existant
    public function displayPlayersForExistingMatch($id_match)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur order by nom, prenom');
            $req->execute();
            $players = $req->fetchAll();
            $display = "";
            foreach ($players as $player) {
                $display .= $this->displayAPlayerForExistingMatch($id_match, $player['id_joueur'], $player['nom'], $player['prenom'], $player['numero_de_licence'], $player['photo'], $player['date_de_naissance'], $player['poid'], $player['taille'], $player['poste_prefere'], $player['statut'], $player['commentaire']);
            }
            return $display;
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage des joueurs : ' . $e->getMessage());
        }
    }

    
    public function displayPlayersFromMatch($idMatch)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT j.nom, j.prenom, j.photo, j.date_de_naissance, p.role FROM Joueur as j, Participer as p WHERE j.id_joueur = p.id_joueur AND p.id_game = :idMatch order by p.role DESC');
            $req->execute(array('idMatch' => $idMatch));
            $players = $req->fetchAll();
            $display = "";
            foreach ($players as $player) {
                $display .= $this->displayPlayerSummary($player['nom'], $player['prenom'], $player['photo'], $player['date_de_naissance'], $player['role']);
            }
            return $display;
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage des joueurs : ' . $e->getMessage());
        }
    }

    //Affiche les joueurs pour l'evaluation
    public function displayPlayersForEvaluation($idMatch)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT j.nom, j.prenom, j.photo, j.date_de_naissance, p.role, p.note FROM Joueur as j, Participer as p WHERE j.id_joueur = p.id_joueur AND p.id_game = :idMatch order by p.role');
            $req->execute(array('idMatch' => $idMatch));
            $players = $req->fetchAll();
            $display = "";
            foreach ($players as $player) {
                $display .= $this->displayPlayerSummaryForEvaluation($player['nom'], $player['prenom'], $player['photo'], $player['date_de_naissance'], $player['role'], $player['note']);
            }
            return $display;
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage des joueurs : ' . $e->getMessage());
        }
    }

    public function displayPlayerSummaryForEvaluation($name, $surname, $picture, $birthday, $state, $note = 1)
    {
        $picture = $this->IMG_DIR . $picture;
        $year = date('Y') - date('Y', strtotime($birthday));
        if ($state == null) {
            $state = "Aucun statut";
        }

        // Persistence des données, permet d'afficher directement les notes déjà entrées par l'utilisateur
        $isChecked1 = "checked";
        $isChecked2 = "";
        $isChecked3 = "";
        $isChecked4 = "";
        $isChecked5 = "";
        $label1TextState = "text-yellow-500";
        $label2TextState = "text-gray-300";
        $label3TextState = "text-gray-300";
        $label4TextState = "text-gray-300";
        $label5TextState = "text-gray-300";
        switch ($note) {
            case 2:
                $isChecked2 = "checked";
                $label2TextState = "text-yellow-500";
                break;
            case 3:
                $isChecked3 = "checked";
                $label2TextState = "text-yellow-500";
                $label3TextState = "text-yellow-500";
                break;
            case 4:
                $isChecked4 = "checked";
                $label2TextState = "text-yellow-500";
                $label3TextState = "text-yellow-500";
                $label4TextState = "text-yellow-500";
                break;
            case 5:
                $isChecked5 = "checked";
                $label2TextState = "text-yellow-500";
                $label3TextState = "text-yellow-500";
                $label4TextState = "text-yellow-500";
                $label5TextState = "text-yellow-500";
                break;
            default:
                break;
        }

        return '<li class="pb-3 sm:pb-4" >
            <div class="flex items-center space-x-4 py-[3px]">
                <div class="flex-shrink-0">
                    <img class="w-8 h-8 rounded-full" src="' . $picture . '" alt="image du joueur">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate">
                        ' . $surname . ' ' . $name . ' - ' . $year . ' ans
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        ' . $state . '
                    </p>
                </div>
                <div class="flex items-center">
                    <input class="hidden" type="radio" name="rating-' . $name . '" id="rating-1-' . $name . '" value="1" '.$isChecked1.'>
                    <label for="rating-1-' . $name . '" class="eval-label-' . $name . ' w-6 h-6 '.$label1TextState.' hover:text-yellow-600 fill-current cursor-pointer" onclick="labelClicked(this)" id="1">
                        <svg class="w-full" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                        </svg>
                    </label>
                    <input class="hidden" type="radio" name="rating-' . $name . '" id="rating-2-' . $name . '" value="2"  '.$isChecked2.'>
                    <label for="rating-2-' . $name . '" class="eval-label-' . $name . ' w-6 h-6 '.$label2TextState.' hover:text-yellow-600 fill-current cursor-pointer" onclick="labelClicked(this)" id="2">
                        <svg class="w-full" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                        </svg>
                    </label>
                    <input class="hidden" type="radio" name="rating-' . $name . '" id="rating-3-' . $name . '" value="3"  '.$isChecked3.'>
                    <label for="rating-3-' . $name . '" class="eval-label-' . $name . ' w-6 h-6 '.$label3TextState.' hover:text-yellow-600 fill-current cursor-pointer" onclick="labelClicked(this)" id="3">
                        <svg class="w-full" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                        </svg>
                    </label>
                    <input class="hidden" type="radio" name="rating-' . $name . '" id="rating-4-' . $name . '" value="4"  '.$isChecked4.'>
                    <label for="rating-4-' . $name . '" class="eval-label-' . $name . ' w-6 h-6 '.$label4TextState.' hover:text-yellow-600 fill-current cursor-pointer" onclick="labelClicked(this)" id="4">
                        <svg class="w-full" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                        </svg>
                    </label>
                    <input class="hidden" type="radio" name="rating-' . $name . '" id="rating-5-' . $name . '" value="5"  '.$isChecked5.'>
                    <label for="rating-5-' . $name . '" class="eval-label-' . $name . ' w-6 h-6 '.$label5TextState.' hover:text-yellow-600 fill-current cursor-pointer" onclick="labelClicked(this)" id="5">
                        <svg class="w-full" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                        </svg>
                    </label>
                </div>
            </div>
        </li>';
    }

    //Recuperatin des noms des joueurs d'un match
    public function getPlayerNameArrayFromMatch($idMatch)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT j.nom, j.numero_de_licence FROM Joueur j, Participer p WHERE j.id_joueur = p.id_joueur AND p.id_game = :idMatch ORDER BY p.role desc');
            $req->execute(array(
                'idMatch' => $idMatch
            ));
            return $req;
        }catch(Exception $e){
            echo('Erreur lors de l\'affichage des joueurs : ' . $e->getMessage());
        }
    }
    //Ajout d'une note à un joueur pour un match
    public function addPlayerRating($idMatch, $idPlayer, $rating)
    {
        try{
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('UPDATE Participer SET note = :rating WHERE id_game = :idMatch AND id_joueur = :idPlayer');
        $req->execute(array(
            'idMatch' => $idMatch,
            'idPlayer' => $idPlayer,
            'rating' => $rating
        ));
    }catch(Exception $e){
        echo('Erreur lors de l\'ajout de la note du joueurs : ' . $e->getMessage());
    }
    }

    //Retourne un joueur en fonction de son ID
    public function getPlayer($id)
    {
        try{
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur WHERE id_joueur = :id');
            $req->execute(array(
                'id' => $id
            ));
            return $req;
        }catch(Exception $e){
            echo('Erreur player non trouvé : ' . $e->getMessage());
        }
    }

    //Modification d'un joueur 
    public function updatePlayer($id, $name, $lastname, $picture_path, $lience, $birthday, $weight, $size, $position, $state, $comment)
    {
        try {
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('UPDATE Joueur SET nom = :lastname, prenom = :name, photo = :picture_path, numero_de_licence = :lience, date_de_naissance = :birthday, poid = :weight, taille = :size, poste_prefere = :position, commentaire = :comment, statut = :state WHERE id_joueur = :id');
            $req->execute(array(
                'id' => $id,
                'name' => $name,
                'lastname' => $lastname,
                'picture_path' => $picture_path,
                'lience' => $lience,
                'birthday' => $birthday,
                'weight' => $weight,
                'size' => $size,
                'position' => $position,
                'comment' => $comment,
                'state' => $state
            ));
        }catch(Exception $e){
            echo('Erreur lors de la modification d\'un joueur: ' . $e->getMessage());
        }
    }

    //Vérifie si un joueur existe déjà (pour modification joueur)
    public function playerExistUpdate($licence, $name, $lastname, $id)
    {
        try {
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur WHERE (numero_de_licence = :licence OR (upper(nom)= upper(:lastname) AND upper(prenom) = upper(:name)))');
            $req->execute(array(
                'licence' => $licence,
                'name' => $name,
                'lastname' => $lastname
            ));
            //Si le joueur existe déjà et que ce n'est pas le joueur en cours de modification
            if ($req->rowCount() > 0) {
                while ($data = $req->fetch()) {
                    if ($data['id_joueur'] != $id) {
                        return true;
                    }
                }
            }
        }catch(Exception $e){
            echo('Erreur lors de la verification de si un joueur existe deja : ' . $e->getMessage());
        }
        return false;
    }

    //Suppression d'un joueur
    public function deletePlayer($id)
    {
        try{
            $sql = $this->sql->getConnection();
            // Suppression des participations du joueur
            $req = $sql->prepare('DELETE FROM Participer WHERE id_joueur = :id');
            $req->execute(array(
                'id' => $id
            ));
            // Suppression du joueur
            $req = $sql->prepare('DELETE FROM Joueur WHERE id_joueur = :id');
            $req->execute(array(
                'id' => $id
            ));
        }catch(Exception $e){
            echo('Erreur lors de la supression d\'un joueur: ' . $e->getMessage());
        }
    }
}
