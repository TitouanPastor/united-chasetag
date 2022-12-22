<?php

    class Player  {
        private $sql;
        public function __construct() {
            require_once('../BDD.php');
            $this->sql = new connectBDD();
        }

        public function addPlayer($name, $lastname, $picture_path, $lience, $birthday, $weight, $size, $position){
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
            $req->closeCursor();
            $this->sql->closeConnection();
        }
        
        public function playerExist($licence, $name, $lastname){
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('SELECT * FROM Joueur WHERE numero_de_licence = :licence OR (upper(nom )= upper(:name) AND upper(prenom) = upper(:lastname))');
            $req->execute(array(
                'licence' => $licence,
                'name' => $name,
                'lastname' => $lastname
            ));
            //si le resultat de la requete à plus d'une ligne, alors le joueur existe déjà
            if($req->rowCount() > 0){
                $req->closeCursor();
                $this->sql->closeConnection();
                return true;
            }
            $req->closeCursor();
            $this->sql->closeConnection();
            return true;
    
        }

       
    }