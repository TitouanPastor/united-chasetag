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
        $req = $sql->prepare('INSERT INTO Game VALUES (null, :date, :hour, :opponents, :location, null)');
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

    public function displayAMatch($id, $date, $hour, $opponents, $location)
    {
        $display = '
        <div class="w-1/4 border border-black h-auto rounded">
                <div class="flex flex-col justify-between py-4 h-full">
                    <div class="flex flex-col border-b border-black pb-4">
                        <span class="text-sm px-4">'.date('d/m/Y',strtotime($date)).' à '.date('H:i',strtotime($hour)).'</span>
                        <span class="text-2xl font-medium px-4">'.$location.'</span>
                        <span class="text-xl font-medium px-4">United / '.$opponents.'</span>
                    </div>
                    <div class="border-b border-black h-full">
                        <ul class="p-4">';

        $display .= $this->player->displayPlayersFromMatch($id);
        
        
        $display .= '
                        </ul>
                    </div>
                    <div class="w-full flex items-center justify-evenly pt-4 ">
                        <a class="px-4 font-medium" href="">Modifier</a>
                        <a class="px-4 font-medium" href="">Supprimer</a>
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
            $display .= $this->displayAMatch($match['id_game'],$match['date_match'], $match['heure_match'], $match['nom_eq_adv'], $match['lieu']);
        }
        return $display;
    }
}
