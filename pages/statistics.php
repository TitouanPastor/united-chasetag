<?php

class Stats
{
    
    private $sql;

    public function __construct()
    {
        //Connexion à la base de données
        require_once('../BDD.php');
        $this->sql = new connectBDD();
    }

    //Recupere les stats des matchs du club (Total, gagné, perdu, nul) 
    public function getMatchs()
    {
        try {
            $stats_matchs = array();
            $req = $this->sql->getConnection()->prepare("SELECT match_gagne, match_perdu, match_joue	FROM Club");
            $req->execute();
            while ($datas = $req->fetch()) {
                $stats_matchs[0] = $datas['match_joue'];
                $stats_matchs[1] = round($datas['match_gagne'] / $stats_matchs[0]*100,2);
                $stats_matchs[2] = round($datas['match_perdu'] / $stats_matchs[0]*100,2);
                $stats_matchs[3] = round((1 - ($stats_matchs[1]/100 + $stats_matchs[2]/100))*100,2);
            }

            return $stats_matchs;
        }catch(Exception $e){
            echo('Erreur lors de la récupération des statistiques : ' . $e->getMessage());
        }
    }

    // Recupere les roles de chaque joueur sur chaque match
    public function getPositions()
    {
        try {
            $stats_positions = array();
            $req = $this->sql->getConnection()->prepare("SELECT j.* ,COALESCE(t.nb_titulaire, 0) as nb_titulaire, COALESCE(r.nb_remplaçant, 0) as nb_remplaçant
                FROM Joueur j
                LEFT JOIN (
                    SELECT id_joueur, COUNT(*) as nb_titulaire
                    FROM Participer, Game g
                    WHERE role = 'titulaire' AND g.id_game = Participer.id_game AND g.statut = '1'
                    
                    GROUP BY id_joueur
                ) t ON t.id_joueur = j.id_joueur
                LEFT JOIN (
                    SELECT id_joueur, COUNT(*) as nb_remplaçant
                    FROM Participer, Game g
                    WHERE role = 'remplaçant' AND g.id_game = Participer.id_game AND g.statut = '1'
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
                    'license' => $datas['numero_de_licence'],
                    'id' => $datas['id_joueur']
                );

            }
            return $stats_positions;
        }catch(Exception $e){
            echo('Erreur lors de la récuperation des rôles : ' . $e->getMessage());
        }
    }

    // Recupere la moyenne des notes d'un joueur 
    public function getRanking($id){
        try{
            $req = $this->sql->getConnection()->prepare("SELECT round(avg(note),2) as average FROM Participer where id_joueur = :id group by id_joueur");
            $req->execute(array(
                'id' => $id
            ));
            while ($datas = $req->fetch()) {
                return $datas['average'];
            }
        }catch(Exception $e){
            echo('Erreur lors de la récupration des notes : ' . $e->getMessage());
        }
    }

    // Fais la modification des stats du club (des matchs suivant le résultat du match)
    public function updateStats($isWined) {
        try{
            $req = $this->sql->getConnection()->prepare("UPDATE Club SET match_joue = match_joue + 1");
            $req->execute();
            if ($isWined == 1) {
                $req = $this->sql->getConnection()->prepare("UPDATE Club SET match_gagne = match_gagne + 1");
                $req->execute();
            } elseif ($isWined == 0) {
                $req = $this->sql->getConnection()->prepare("UPDATE Club SET match_perdu = match_perdu + 1");
                $req->execute();
            }
        }catch(Exception $e){
            echo('Erreur lors de la mise à jour des résultats : ' . $e->getMessage());
        }
    }
}
