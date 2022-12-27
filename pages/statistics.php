<?php

class Stats
{
    public function __construct()
    {
        require_once('../BDD.php');
        $this->sql = new connectBDD();
    }

    public function getMatchs()
    {
        $stats_matchs = array();
        $req = $this->sql->getConnection()->prepare("SELECT match_gagne, match_perdu, match_joue	FROM Club");
        $req->execute();
        while ($datas = $req->fetch()) {
            $stats_matchs[0] = $datas['match_joue'];
            $stats_matchs[1] = $datas['match_gagne'] / $stats_matchs[0];
            $stats_matchs[2] = $datas['match_perdu'] / $stats_matchs[0];
            $stats_matchs[3] = 1 - ($stats_matchs[1] + $stats_matchs[2]);
        }

        return $stats_matchs;
    }

    // Recupere les roles de chaque joueur sur chaque match
    public function getPositions()
    {
        $stats_positions = array();
        $req = $this->sql->getConnection()->prepare("SELECT j.* ,COALESCE(t.nb_titulaire, 0) as nb_titulaire, COALESCE(r.nb_remplaçant, 0) as nb_remplaçant
            FROM Joueur j
            LEFT JOIN (
                SELECT id_joueur, COUNT(*) as nb_titulaire
                FROM Participer
                WHERE role = 'titulaire'
                GROUP BY id_joueur
            ) t ON t.id_joueur = j.id_joueur
            LEFT JOIN (
                SELECT id_joueur, COUNT(*) as nb_remplaçant
                FROM Participer
                WHERE role = 'remplaçant'
                GROUP BY id_joueur
            ) r ON r.id_joueur = j.id_joueur");
        $req->execute();    
        while ($datas = $req->fetch()) {
            $stats_positions[$datas['nom'].' '.$datas['prenom']] = array(
                'nb_owner' => $datas['nb_titulaire'],
                'nb_alternate' => $datas['nb_remplaçant'],
                'picture' =>  '../imgPlayers/'.$datas['photo'],
                'state' => $datas['statut'],
                'fav_position' => $datas['poste_prefere'],
                'license' => $datas['numero_de_licence']
            );

        }

        return $stats_positions;
    }
}
