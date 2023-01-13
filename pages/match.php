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

    // Fonction permettant d'ajouter un match avec les différents paramètres
    public function addMatch($date, $hour, $opponents, $location, $domi_ext)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('INSERT INTO Game VALUES (null, :date, :hour, :opponents, :location, null, null, :domi_ext, null)');
        $req->execute(array(
            'date' => $date,
            'hour' => $hour,
            'opponents' => $opponents,
            'location' => $location,
            'domi_ext' => $domi_ext
        ));
    }

    // Fonction permettant d'ajouter un joueur à un match avec son role pendant le match
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

    // Fonction qui permet de supprimer tout les joueurs participant à un match
    public function dropMatchAllPlayers($id_match)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('DELETE FROM Participer WHERE id_game = :id_match');
        $req->execute(array(
            'id_match' => $id_match
        ));
    }

    //  Fonction permettant de supprimer entièrement un match
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


    // Fonction permettant de savori si un match existe ou non
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

    // Fonction retournant l'id d'un match à partir de sa date et de son heure
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

    // Fonction retournant un match avec son id
    public function getMatch($id)
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Game WHERE id_game = :id');
        $req->execute(array(
            'id' => $id
        ));
        return $req;
    }

    // Fonction permettant de retourner dans une chaine de caractères les infos d'un match
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

    // Fonction permettant de retourner dans une chaine de caractères HTML les infos d'un match complètes (disponible sur la page de liste des matchs)
    public function displayAMatch($id, $date, $hour, $opponents, $location, $score_equipe, $score_adv, $domi_ext)
    {
 
        $bg_match = '';
        $is_match_passed = false;
        $span_is_finished = '';
        // On vérifie si le match est passé ou non et on change la couleur de l'affichage
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $bg_match = 'bg-gray-200';
            $span_is_finished = '<span class="text-l font-medium px-4 text-red-600">Match terminé</span>';
            $is_match_passed = true;
        }

        // On vérifie si le match est domicile ou extérieur et on change le sens de l'affichage
        if ($domi_ext == 'Domicile') {
            $div_domi_ext = '<span class="text-xl font-medium px-4">United / ' . $opponents . '</span>';
        } else {
            $div_domi_ext = '<span class="text-xl font-medium px-4">' . $opponents . ' / United</span>';
        }

        $display = '
        <div class="w-[300px] border-2 '.$bg_match.'  border-purple-800  h-auto rounded hover:scale-[102%] hover:shadow-xl transition-all">
                <div class="flex flex-col justify-between py-4 h-full">
                    <div class="flex flex-col border-b border-purple-800 pb-4">
                    '.$span_is_finished.'
                        <span class="text-sm px-4">' . date('d/m/Y', strtotime($date)) . ' à ' . date('H:i', strtotime($hour)) . '</span>
                        <span class="text-2xl font-medium px-4">' . $location . '</span>
                        '.$div_domi_ext.'
                    </div>
                    <div class="border-b border-purple-800  h-full">
                        <ul class="p-4">';

        // On affiche les joeurs du match
        $display .= $this->player->displayPlayersFromMatch($id);

        $display .= '
                        </ul>
                    </div>';

        // On affiche le score du match
        if ($score_equipe != null && $score_adv != null) {
            if ($score_equipe > $score_adv) {
                $resultat = 'Victoire';
            } else if ($score_equipe < $score_adv) {
                $resultat = 'Défaite';
            } else {
                $resultat = 'Match nul';
            }
            $display .= '
                    <div class="w-full flex items-center justify-center py-2 border-b border-purple-800 ">
                        <span class="px-4 font-medium">' . $resultat . ' :</span>
                        <span class="px-4 font-medium">' . $score_equipe . ' - ' . $score_adv . '</span>
                    </div>';
        }
        //On crypte l'id du match pour le passer en paramètre dans l'url
        $idMatchencode = openssl_encrypt($id, "aes-256-ecb", "toto");
        // On l'encode en base64 pour éviter les problèmes d'encodage
        $idMatchencode = base64_encode($idMatchencode);

        if ($is_match_passed == false) {
            $display .= '
                    <div class="w-full flex items-center justify-evenly pt-4 ">
                        <a class="px-4 font-medium" href="editMatch.php?id=' . $idMatchencode . '">Modifier</a>
                        <a class="px-4 font-medium" value="displayMatchs.php?id_del=' . $idMatchencode . '" href="" onclick="openPopUp(this)" >Supprimer</a>
                    </div>
                </div>
            </div>
            ';
        } else {
            $display .= '
                    <div class="w-full flex items-center justify-evenly pt-4 ">
                    <a class="px-4 font-medium" href="scoreMatch.php?id=' . $idMatchencode . '">Score</a>
                        <a class="px-4 font-medium" href="evaluateMatch.php?id=' . $idMatchencode . '">Evaluer</a>
                    </div>
                </div>
            </div>
            ';
        }
        return $display;
    }

    // Fonction permettant d'afficher au format HTML tous les matchs
    public function displayAllMatchs()
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Game ORDER BY date_match');
        $req->execute();
        $matchs = $req->fetchAll();
        $display = "";
        foreach ($matchs as $match) {
            $display .= $this->displayAMatch($match['id_game'], $match['date_match'], $match['heure_match'], $match['nom_eq_adv'], $match['lieu'], $match['score_equipe'], $match['score_adv'], $match['domi_ext']);
        }
        // Si il n'y a aucun match à afficher, on affiche un message
        if ($display == "") {
            $display = '<span class="text-xl font-medium">Aucun match à afficher</span>';
        }
        return $display;
    }

    // Fonction permettant de modifier un match dans le BDD
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

    public function matchToFinished($idMatch) {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('UPDATE Game SET statut = 1 WHERE id_game = :id');
        $req->execute(array(
            'id' => $idMatch
        ));
    }

    public function getMatchStatus($idMatch) {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT statut FROM Game WHERE id_game = :id');
        $req->execute(array(
            'id' => $idMatch
        ));
        $status = $req->fetch();
        return $status['statut'];
    }
}
