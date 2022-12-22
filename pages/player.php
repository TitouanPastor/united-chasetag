<?php

    class Player  {
        private $sql;
        public function __construct() {
            require_once('../BDD.php');
            $this->sql = new connectBDD();
        }

        public function addPlayer($name, $lastname, $picture_path, $lience, $birthday, $weight, $size){
            $sql = $this->sql->getConnection();
            $req = $sql->prepare('INSERT INTO Joueur  VALUES (null, :name, :lastname, :picture_path, :lience, :birthday, :size, :weight, null, null, null)');
            $req->execute(array(
                'name' => $name,
                'lastname' => $lastname,
                'picture_path' => $picture_path,
                'lience' => $lience,
                'birthday' => $birthday,
                'weight' => $weight,
                'size' => $size
            ));

            $req->closeCursor();
            $this->sql->closeConnection();
        }

       
    }