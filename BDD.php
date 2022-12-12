<?php

    class connectBDD{
        private $kinkpdo;

        public function __construct(){
        ///Connexion au serveur MySQL avec PDO
        $server = '54.37.31.19';
        $login  = 'u743447366_chasetag';
        $mdp    = 'F|M2LQBo6';
        $db     = 'u743447366_unitedchasetag';

        try {
            $this->linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);
            $this->linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Connexion reussi !';
        }
        ///Capture des erreurs éventuelles
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }


        }
    }

?>