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
        if ($state == null){
            $state = "Aucun statut";
        }
        if ($comment == null){
            $comment = "Aucun commentaire";
        }

        $picture = $this->IMG_DIR . $picture;
        return '<li class="pb-3 sm:pb-4" >
            <div class="flex items-center space-x-4 my-4">
                <div class="flex-shrink-0">
                    <img class="w-16 h-16 rounded-full" src="'.$picture.'" alt="Photo de '.$lastname.' '.$name.'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-m font-medium text-gray-900 truncate ">
                        ['.$licence.'] - '.$lastname.' '.$name.' - '.$year.' ans - '.$weight.' KG - '.$size.' CM
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400">
                        '.$position.' - '.$state.'
                    </p>
                    <p class="text-m text-gray-500 truncate dark:text-gray-400 whitespace-normal   ">
                        Commentaire : '.$comment.'
                    </p>
                </div>
                <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                    <a href="editPlayer.php?id='.$id.'" class="p-2"> MODIFIER </a> 
                    <button type="submit" name="delete"><a href="displayPlayers.php?id='.$id.'" class="p-2" name="delete"> SUPPRIMER </a></button>
                </div>
            </div>
        </li>';     
    }

    public function displayPlayers(){
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

    public function getPlayer($id){
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Joueur WHERE id_joueur = :id');
        $req->execute(array(
            'id' => $id
        ));
        return $req;
    }

    public function updatePlayer($id, $name, $lastname, $picture_path, $lience, $birthday, $weight, $size, $position, $state, $comment){
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
        $req = $sql->prepare('SELECT * FROM Joueur WHERE (numero_de_licence = :licence OR (upper(nom)= upper(:lastname) AND upper(prenom) = upper(:name)))' );
        $req->execute(array(
            'licence' => $licence,
            'name' => $name,
            'lastname' => $lastname
        ));
        //si le resultat de la requete à plus d'une ligne, alors le joueur existe déjà
        if ($req->rowCount() > 0) {
            while($data = $req->fetch()){
                if($data['id_joueur'] != $id){
                    return true;
                }
            }
            
        }
        return false;
    }

    public function deletePlayer($id){
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('DELETE FROM Joueur WHERE id_joueur = :id');
        $req->execute(array(
            'id' => $id
        ));
    }
}
