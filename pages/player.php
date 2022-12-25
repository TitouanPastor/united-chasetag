<?php

class Player
{
    private $sql;
    private $IMG_DIR = '../imgPlayers/';

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

    public function displayAPlayer($name, $lastname, $licence, $picture, $birthday, $weight, $size, $position, $state, $comment)
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
                <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                    <a href="#" class="p-2"> MODIFIER  </a> 
                    <a href="#" class="p-2"> SUPPRIMER </a>
                </div>
            </div>
        </li>';
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
                <input type="checkbox" onclick="toggleRadiosSelection(\''.$licence.'\')" class="accent-purple-800 w-6 h-6" name="playerlicense[]" value="' . $licence . '">
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

    public function displayPlayers()
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Joueur');
        $req->execute();
        $players = $req->fetchAll();
        $req->closeCursor();
        $this->sql->closeConnection();
        $display = "";
        foreach ($players as $player) {
            $display .= $this->displayAPlayer($player['nom'], $player['prenom'], $player['numero_de_licence'], $player['photo'], $player['date_de_naissance'], $player['poid'], $player['taille'], $player['poste_prefere'], $player['statut'], $player['commentaire']);
        }
        return $display;
    }

    public function displayPlayersSelection()
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Joueur');
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

    public function displayPlayersFromMatch($idMatch) {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT j.nom, j.prenom, j.photo, j.date_de_naissance, p.role FROM Joueur as j, Participer as p WHERE j.id_joueur = p.id_joueur AND p.id_game = :idMatch');
        $req->execute(array('idMatch' => $idMatch));
        $players = $req->fetchAll();
        $display = "";
        foreach ($players as $player) {
            $display .= $this->displayPlayerSummary($player['nom'], $player['prenom'], $player['photo'], $player['date_de_naissance'], $player['role']);
        }
        return $display;
    }
}
