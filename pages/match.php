<?php

class Matchs
{
    private $sql;

    public function __construct()
    {
        require_once('../BDD.php');
        $this->sql = new connectBDD();
    }

    public function addMatch($date, $hour, $opponents, $location) {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('INSERT INTO Game VALUES (null, :date, :hour, :opponents, :location, null)');
        $req->execute(array(
            'date' => $date,
            'hour' => $hour,
            'opponents' => $opponents,
            'location' => $location
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

    public function displayAMatch($date, $hour, $team1, $team2, $score1, $score2, $place, $comment)
    {
        if ($comment == null){
            $comment = "Aucun commentaire";
        }
        return '<li class="pb-3 sm:pb-4" >
            <div class="flex items center space-x-4 my-4">
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-3 bg-white text-lg font-medium text-gray-900">
                                ' . $date . '
                            </span>
                        </div>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">
                        ' . $team1 . ' VS ' . $team2 . '
                    </div>
                    <div class="text-sm text-gray-500">
                        ' . $hour . ' - ' . $place . '
                    </div>
                    <div class="text-sm text-gray-500">
                        ' . $score1 . ' - ' . $score2 . '
                    </div>
                    <div class="text-sm text-gray-500">
                        ' . $comment . '
                    </div>
                </div>
            </div>
        </li>';
    }
    public function displayAllMatchs()
    {
        $sql = $this->sql->getConnection();
        $req = $sql->prepare('SELECT * FROM Matchs');
        $req->execute();
        $matchs = $req->fetchAll();
        $display = "";
        foreach ($matchs as $match) {
            $display .= $this->displayAMatch($match['date'], $match['heure'], $match['equipe1'], $match['equipe2'], $match['score1'], $match['score2'], $match['lieu'], $match['commentaire']);
        }
        return $display;
    }
}