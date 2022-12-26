<?php

class Matchs
{
    private $sql;
    private $player;

    public function __construct()
    {
        require_once('../BDD.php');
        $this->sql = new connectBDD();
        require_once('Player.php');
        $this->player = new Player();
    }

    public function addMatch($date, $hour, $opponents, $location)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('INSERT INTO Game VALUES (null, :date, :hour, :opponents, :location, null, null)');
        $req->execute(array(
            'date' => $date,
            'hour' => $hour,
            'opponents' => $opponents,
            'location' => $location
        ));
    }

    public function addMatchPlayer($id_match, $id_player, $role)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('INSERT INTO Participer VALUES (:id_player, :id_match, :role, null)');
        $req->execute(array(
            'id_match' => $id_match,
            'id_player' => $id_player,
            'role' => $role
        ));
    }

    // $sql = $this->sql->getConnection();
    // // On vérifie si le joueur est déjà inscrit au match, et sont rôle est différent du rôle qu'on veut lui attribuer
    // $player_is_playing_match = $this->player->playerIsPlayingAMatch($id_match, $id_player);
    // if ($player_is_playing_match == "titulaire" || $player_is_playing_match == "remplaçant") {
    //     if ($player_is_playing_match != $role) {
    //         // Si oui, on met à jour son rôle
    //         $req = $sql->prepare('UPDATE Participer SET role = :role WHERE id_joueur = :id_player AND id_game = :id_match');
    //         $req->execute(array(
    //             'id_match' => $id_match,
    //             'id_player' => $id_player,
    //             'role' => $role
    //         ));
    //     }
    // } else if ($player_is_playing_match == false) {
    //     // Sinon, on l'inscrit au match avec son rôle
    //     $req = $sql->prepare('INSERT INTO Participer VALUES (:id_player, :id_match, :role, null)');
    //     $req->execute(array(
    //         'id_match' => $id_match,
    //         'id_player' => $id_player,
    //         'role' => $role
    //     ));
    // }

    public function dropMatchAllPlayers($id_match)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('DELETE FROM Participer WHERE id_game = :id_match');
        $req->execute(array(
            'id_match' => $id_match
        ));
    }

    public function dropMatch($id_match)
    {
        $sql = $this->sql->getConnection();
        // On drop tout les joueurs du match
        $this->dropMatchAllPlayers($id_match);
        // On drop le match
        $req = $sql->prepare('DELETE FROM Game WHERE id_game = :id_match');
        $req->execute(array(
            'id_match' => $id_match
        ));
    }


    public function matchExist($date, $hour)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Game WHERE date_match = :date AND heure_match = :hour');
        $req->execute(array(
            'date' => $date,
            'hour' => $hour
        ));
        //si le resultat de la requete à plus d'une ligne, alors le match existe déjà
        if ($req->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function getIdMatch($date, $hour)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT id_game FROM Game WHERE date_match = :date AND heure_match = :hour');
        $req->execute(array(
            'date' => $date,
            'hour' => $hour
        ));
        $id = $req->fetch();
        return $id['id_game'];
    }

    public function getMatch($id)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Game WHERE id_game = :id');
        $req->execute(array(
            'id' => $id
        ));
        return $req;
    }

    public function getMatchInfos($id)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT date_match, heure_match, nom_eq_adv FROM Game WHERE id_game = :id');
        $req->execute(array(
            'id' => $id
        ));
        $infos = $req->fetch();
        return 'Le ' . date('d/m/Y', strtotime($infos['date_match'])) . ' à ' . date('H:i', strtotime($infos['heure_match'])) . ' contre ' . $infos['nom_eq_adv'];
    }

    public function displayAMatch($id, $date, $hour, $opponents, $location, $score_equipe, $score_adv)
    {
        $display = '
        <div class="w-[300px] border border-black h-auto rounded">
                <div class="flex flex-col justify-between py-4 h-full">
                    <div class="flex flex-col border-b border-black pb-4">
                        <span class="text-sm px-4">' . date('d/m/Y', strtotime($date)) . ' à ' . date('H:i', strtotime($hour)) . '</span>
                        <span class="text-2xl font-medium px-4">' . $location . '</span>
                        <span class="text-xl font-medium px-4">United / ' . $opponents . '</span>
                    </div>
                    <div class="border-b border-black h-full">
                        <ul class="p-4">';

        $display .= $this->player->displayPlayersFromMatch($id);


        $display .= '
                        </ul>
                    </div>';

        if ($score_equipe != null && $score_adv != null) {
            if ($score_equipe > $score_adv) {
                $resultat = 'Victoire';
            } else if ($score_equipe < $score_adv) {
                $resultat = 'Défaite';
            } else {
                $resultat = 'Match nul';
            }
            $display .= '
                    <div class="w-full flex items-center justify-center py-2 border-b border-black">
                        <span class="px-4 font-medium">' . $resultat . ' :</span>
                        <span class="px-4 font-medium">' . $score_equipe . ' - ' . $score_adv . '</span>
                    </div>';
        }
        //On crypte l'id du match pour le passer en paramètre dans l'url
        $idMatchencode = openssl_encrypt($id, "aes-256-ecb", "toto");
        // On l'encode en base64 pour éviter les problèmes d'encodage
        $idMatchencode = base64_encode($idMatchencode);

        $display .= '
                    <div class="w-full flex items-center justify-evenly pt-4 ">
                        <a class="px-4 font-medium" href="editMatch.php?id=' . $idMatchencode . '">Modifier</a>
                        <a class="px-4 font-medium" href="displayMatchs.php?id_del=' . $idMatchencode . '">Supprimer</a>
                    </div>
                </div>
            </div>
        ';
        return $display;
    }
    public function displayAllMatchs()
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Game ORDER BY date_match');
        $req->execute();
        $matchs = $req->fetchAll();
        $display = "";
        foreach ($matchs as $match) {
            $display .= $this->displayAMatch($match['id_game'], $match['date_match'], $match['heure_match'], $match['nom_eq_adv'], $match['lieu'], $match['score_equipe'], $match['score_adv']);
        }
        if ($display == "") {
            $display = '<span class="text-xl font-medium">Aucun match à afficher</span>';
        }
        return $display;
    }

    public function editMatch($id, $date, $hour, $opponents, $location, $score_equipe, $score_adv)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('UPDATE Game SET date_match = :date, heure_match = :hour, nom_eq_adv = :opponents, lieu = :location, score_equipe = :score_equipe, score_adv = :score_adv WHERE id_game = :id');
        $req->execute(array(
            'id' => $id,
            'date' => $date,
            'hour' => $hour,
            'opponents' => $opponents,
            'location' => $location,
            'score_equipe' => $score_equipe,
            'score_adv' => $score_adv
        ));
    }
}
