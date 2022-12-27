<?php

class Player
{
    private $sql;
    public $IMG_DIR = '../imgPlayers/';

    public function __construct()
    {
        require_once('../BDD.php');
        $this->sql = new connectBDD();
    }

    public function addPlayer($name, $lastname, $picture_path, $lience, $birthday, $weight, $size, $position)
    {
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
    }

    public function playerExist($licence, $name, $lastname)
    {
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
        return false;
    }

    public function displayAPlayer($name, $lastname, $licence, $picture, $birthday, $weight, $size, $position, $state, $comment, $id)
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
                    <a href="editPlayer.php?id=' . $id . '" class="p-2"> MODIFIER </a> 
                    <a href="displayPlayers.php?deletePlayer=' . $id . '" class="p-2" name="delete"> SUPPRIMER </a>
                </div>
            </div>
        </li>';
    }

    public function getIdPlayer($licence)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT id_joueur FROM Joueur WHERE numero_de_licence = :licence');
        $req->execute(array(
            'licence' => $licence
        ));
        $id = $req->fetch();
        return $id['id_joueur'];
    }

    public function displayPlayers()
    {
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
    }

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

    public function displayPlayersSelection()
    {
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
        return $display;
    }

    public function playerIsPlayingAMatch($id_match, $id_player)
    {
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
                '.$div_selectionne.'
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
                        '.$div_titulaire.'
                        <label for="titulaire">Titulaire</label>
                    </div>
                    <div class="flex gap-2 text-base font-semibold text-gray-900 ">
                        '.$div_remplacant.'
                        <label for="remplacant">Remplacant</label>
                    </div>
                </div>
            </div>
        </li>';
    }

    public function displayPlayersForExistingMatch($id_match)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Joueur order by nom, prenom');
        $req->execute();
        $players = $req->fetchAll();
        $display = "";
        foreach ($players as $player) {
            $display .= $this->displayAPlayerForExistingMatch($id_match, $player['id_joueur'], $player['nom'], $player['prenom'], $player['numero_de_licence'], $player['photo'], $player['date_de_naissance'], $player['poid'], $player['taille'], $player['poste_prefere'], $player['statut'], $player['commentaire']);
        }
        return $display;
    }

    public function displayPlayersFromMatch($idMatch)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT j.nom, j.prenom, j.photo, j.date_de_naissance, p.role FROM Joueur as j, Participer as p WHERE j.id_joueur = p.id_joueur AND p.id_game = :idMatch order by p.role DESC');
        $req->execute(array('idMatch' => $idMatch));
        $players = $req->fetchAll();
        $display = "";
        foreach ($players as $player) {
            $display .= $this->displayPlayerSummary($player['nom'], $player['prenom'], $player['photo'], $player['date_de_naissance'], $player['role']);
        }
        return $display;
    }

    public function getPlayer($id)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Joueur WHERE id_joueur = :id');
        $req->execute(array(
            'id' => $id
        ));
        return $req;
    }

    public function updatePlayer($id, $name, $lastname, $picture_path, $lience, $birthday, $weight, $size, $position, $state, $comment)
    {
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
    }

    public function playerExistUpdate($licence, $name, $lastname, $id)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Joueur WHERE (numero_de_licence = :licence OR (upper(nom)= upper(:lastname) AND upper(prenom) = upper(:name)))');
        $req->execute(array(
            'licence' => $licence,
            'name' => $name,
            'lastname' => $lastname
        ));
        //si le resultat de la requete à plus d'une ligne, alors le joueur existe déjà
        if ($req->rowCount() > 0) {
            while ($data = $req->fetch()) {
                if ($data['id_joueur'] != $id) {
                    return true;
                }
            }
        }
        return false;
    }

    public function deletePlayer($id)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('DELETE FROM Joueur WHERE id_joueur = :id');
        $req->execute(array(
            'id' => $id
        ));
    }
}
