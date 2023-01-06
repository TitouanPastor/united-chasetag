<?php

// Classe permettant de faire l'authentification de l'utilisateur (login)
class Auth
{
    private $sql;

    public function __construct()
    {
        require_once('../BDD.php');
        $this->sql = new connectBDD();
    }

    // Fonction permettant de vérifier si l'utilisateur existe dans la base de données et si les identifiants sont corrects
    public function checkAuth($mail, $password)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT count(*) FROM utilisateurs WHERE email = :mail AND Mot_de_passe = :password');
        $req->execute(array(
            'mail' => $mail,
            'password' => $password
        ));

        $result = $req->fetch();
        //condition si il y a un résultat
        if ($result[0] != 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
