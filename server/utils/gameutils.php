<?php 
require_once "creds.php";
require_once "mysql.php";


class GameUtils {
    public $id;

    private $creds;
    private $mysql;
    

    public function __construct($id) {
    $this->id = $id;

    $this->creds = new Creds();
    $this->mysql = new MySQLDatabase($this->creds);
    }

    function cycle($player_id, $game_id) {
        $this->mysql->update("cards_$this->id", array("card_loc" => "deck"), "card_loc = 'discard_ '". $this->getNextPlayer($player_id) . "'");

        $query = "SELECT card_id FROM cards_$this->id WHERE card_loc = 'discard_$player_id' ORDER BY RAND() LIMIT 1";
        $result = $this->mysql->query($query);
        $card_id = $result[0]["card_id"];

        $this->mysql->update("cards_$this->id", array("card_loc" => "hand_$player_id"), "card_id = $card_id");
    }

    function getCards($player_id) {
        $result = $this->mysql->select("cards_$this->id", "*", "card_loc = 'hand_$player_id'");
        return $result;
    }

    function getNextPlayer($player_id) {
        $next = $player_id + 1;
        if ($next > $this->getNumPlayers() - 1) {
            $next = 1;
        }
        return $next;
    }

    function getNumPlayers() {
        $players = $this->mysql->select("game_$this->id", "players");
        return count($players);
    }

    function checkForWinningHand($player_id) {
        $cards = $this->getCards($player_id);
        $value = $cards[0]["card_value"];
        for ($i = 1; $i < count($cards); $i++) {
            if ($cards[$i]["card_value"] != $value) {
                return false;
            }
        }
        return true;
    }

    function takeSpoon($player_id) {
        $this->mysql->update("game_$this->id", array("has_spoon" => "1"), "player_id = $player_id");
        
    }

    function checkForSpoonTaken() {
        $players = $this->mysql->select("game_$this->id", "players");
        foreach ($players as $player) {
            if ($player["has_spoon"] == "1") {
                return true;
            }
        }
        return false;
    }

    function numSpoonsLeft() {
        $players = $this->mysql->select("game_$this->id", "players");
        $num_spoons = 0;
        foreach ($players as $player) {
            if ($player["has_spoon"] == "1") {
                $num_spoons += 1;
            }
        }
        return $this->getNumPlayers() - 1 - $num_spoons;
    }

    function checkForEndOfRound() {
        if ($this->numSpoonsLeft() == 0) {
            return true;
        }
        return false;
    }

    function playerJoined($player_id) {
        $this->mysql->insert("game_$this->id", array("players" => $player_id));
        $this->mysql->update("users", array("in_game" => "$this->id"), "id = $player_id");
    }

    function playerLeft($player_id) {
        $this->mysql->delete("game_$this->id", "players = $player_id");
        $this->mysql->update("users", array("in_game" => "0"), "id = $player_id");
    }

    function getGameCode() {
        return $this->id;
    }


    

?>