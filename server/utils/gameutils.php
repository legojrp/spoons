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

    /**
     * Cycles the cards in the game for a given player.
     *
     * @param int $player_id The ID of the player whose cards are being cycled.
     * @param int $game_id The ID of the game.
     * @throws None
     * @return void
     */
    function cycle($player_id, $card_id) {
        $this->mysql->update("cards_$this->id", array("card_loc" => "discard_" . $this->getNextPlayer($player_id)), "card_id = '$card_id'");

        $query = "SELECT card_id FROM cards_$this->id WHERE card_loc = 'discard_$player_id' ORDER BY RAND() LIMIT 1";
        $result = $this->mysql->query($query, true);
        $card_id = $result[0]["card_id"];

        $this->mysql->update("cards_$this->id", array("card_loc" => "hand_$player_id"), "card_id = $card_id");
    }

    /**
     * Retrieves the cards in the hand of a given player.
     *
     * @param int $player_id The ID of the player whose cards are being retrieved.
     * @return array An array of cards in the hand of the player.
     */
    function getCards($player_id) {
        $result = $this->mysql->select("cards_$this->id", "*", "WHERE card_loc = 'hand_$player_id'");
        return $result;
    }

    /**
     * Returns the ID of the next player in a game.
     *
     * @param int $player_id The ID of the current player.
     * @return int The ID of the next player.
     */
    function getNextPlayer($player_id) {
        $next = $player_id + 1;
        if ($next > $this->getNumPlayers()) {
            $next = 1;
        }
        return $next;
    }

    /**
     * Retrieves the number of players in the game.
     *
     * @return int The number of players in the game.
     */
    function getNumPlayers() {
        $players = $this->mysql->select("game_$this->id", "user_id");
        return count($players);
    }

    /**
     * Checks if the hand of a given player is a winning hand.
     *
     * @param int $player_id The ID of the player whose hand is being checked.
     * @return bool Returns true if the hand is a winning hand, false otherwise.
     */
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

    /**
     * Updates the "has_spoon" field in the "game_$this->id" table to "1" for the player with the given ID.
     *
     * @param int $player_id The ID of the player whose "has_spoon" field is being updated.
     * @throws None
     * @return void
     */
    function takeSpoon($player_id) {
        $this->mysql->update("game_$this->id", array("has_spoon" => "1"), "player_id = $player_id");
        
    }

    /**
     * Checks if the spoon has been taken in the game.
     *
     * @return bool Returns true if the spoon has been taken, false otherwise.
     */
    function checkForSpoonTaken() {
        $players = $this->mysql->select("game_$this->id", "user_id");
        foreach ($players as $player) {
            if ($player["has_spoon"] == "1") {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculates the number of spoons left in the game.
     *
     * @return int The number of spoons left.
     */
    function getSpoonCount() {
        $players = $this->mysql->select("game_$this->id", "has_spoon");
        $num_spoons = 0;
        foreach ($players as $player) {
            if ($player["has_spoon"] == "1") {
                $num_spoons += 1;
            }
        }
        return $this->getNumPlayers() - 1 - $num_spoons;
    }

    /**
     * Checks if the end of the round has been reached.
     *
     * @return bool Returns true if the end of the round has been reached, false otherwise.
     */
    function checkForEndOfRound() {
        if ($this->getSpoonCount() == 0) {
            return true;
        }
        return false;
    }

    /**
     * Inserts a new record into the "game_$this->id" table with the given player ID and updates the "in_game" field
     * in the "users" table for the player with the given ID.
     *
     * @param int $player_id The ID of the player who has joined the game.
     * @throws None
     * @return void
     */
    function playerJoined($player_id) {
        $this->mysql->insert("game_$this->id", array("user_id" => $player_id));
        $this->mysql->update("users", array("in_game" => "$this->id"), "user_id = $player_id");
    }

    /**
     * Removes a player from the game and updates their in-game status.
     *
     * @param int $player_id The ID of the player to be removed.
     * @throws None
     * @return void
     */
    function playerLeft($player_id) {
        $this->mysql->delete("game_$this->id", "user_id = $player_id");
        $this->mysql->update("users", array("in_game" => "0"), "id = $player_id");
    }

    /**
     * Retrieves the game code associated with the current object.
     *
     * @return int The game code.
     */
    function getGameCode() {
        return $this->id;
    }

    /**
     * Initializes the cards table for the current game and populates it with data from a JSON file.
     *
     * @throws Exception If there is an error creating the table or inserting data into it.
     * @return void
     */
    function initCards() {
        $creds = new Creds();
        $mysql = new MySQLDatabase($creds);
        $mysql->createTable("cards_$this->id", "card_id int(11) NOT NULL AUTO_INCREMENT, card_loc varchar(255) NOT NULL, card_name varchar(255) NOT NULL, card_value VARCHAR(255) NOT NULL, PRIMARY KEY (card_id)");
        $cards = json_decode(file_get_contents("../../server/utils/cards.json"), true);

        foreach ($cards as $card) {
            $mysql->insert("cards_$this->id", array(
                "card_loc" => $card["location"],
                "card_name" => $card["name"],
                "card_value" => $card["value"]
            ));
        }
        
    }
    
    /**
     * Shuffles the cards in the game for each player.
     *
     * This function shuffles the cards in the game by updating the "card_loc" column of the "cards_$this->id" table.
     * It first sets all cards to have the "deck" location. Then, for each player, it randomly selects 4 cards from the deck
     * and assigns them to the player's hand. If a card is already assigned to a player's hand, it is skipped.
     *
     * @throws None
     * @return void
     */
    function shuffleCards() {
        $creds = new Creds();
        $mysql = new MySQLDatabase($creds);
        $players = $mysql->select("game_$this->id", "*");

        $mysql->update("cards_$this->id", array("card_loc" => "deck") , "1 = 1");

        
        for ($i = 0; $i < count($players); $i++) {
            for ($j = 0; $j < 5; $j++) {
                $random_index = rand(1, 52);
                if ($mysql->select("cards_$this->id", "card_loc", "WHERE card_id = $random_index")[0]["card_loc"] == "deck") {
                    $mysql->update("cards_$this->id", array("card_loc" => "hand_" . $players[$i]["player_id"]) , "card_id = $random_index");
                }
                else {
                    $j -= 1;
                }
            }
        }

        $this->deckToDiscard1();
    }

    /**
     * Starts the game by initializing cards, shuffling them, and updating the "active" field in the "game_list" table.
     *
     * This function initializes the cards for the game by calling the `initCards()` method. It then shuffles the cards
     * by calling the `shuffleCards()` method. Finally, it updates the "active" field in the "game_list" table to "1"
     * for the game with the specified join code.
     *
     * @throws None
     * @return void
     */
    function startGame() {
        $this->initCards();
        $this->shuffleCards();
        $this->mysql->update("game_list", array("active" => "1"), "join_code = '$this->id'");
        
    }

/**
 * Checks if the given player is the host of the game.
 *
 * @param int $player_id The ID of the player to check.
 * @return bool Returns true if the player is the host, false otherwise.
 */
    function isHost($player_id) {
        if (isset($this->mysql->select("game_list", "creator_user_id", "WHERE join_code = '$this->id' AND creator_user_id = '$player_id'")[0]["creator_user_id"])) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the game with the given join code is currently active.
     *
     * This function queries the "game_list" table to check if the game with the specified join code is active.
     * It returns true if the game is active, and false otherwise.
     *
     * @return bool Returns true if the game is active, false otherwise.
     */
    function isActive() {
        if ($this->mysql->select("game_list", "active", "WHERE join_code = '$this->id'")[0]["active"] == "1") {
            return true;
        }
        return false;
    }
    /**
     * Calculates the stage of the game by determining the maximum number of cards held by any player.
     *
     * This function retrieves the player IDs from the "game_$this->id" table and iterates over each player.
     * For each player, it queries the "cards_$this->id" table to retrieve the count of cards held by the player
     * in their hand. The function keeps track of the maximum count encountered and returns it at the end.
     *
     * @return int The maximum number of cards held by any player in the game.
     */
    function calcStage() {
        $players = $this->mysql->select("game_$this->id", "player_id");
        $max_cards = 0;
        foreach ($players as $player) {
            $cards = $this->mysql->select("cards_$this->id", "card_value, count(card_id) as count", "WHERE card_loc = 'hand_" . $player["player_id"] . "' GROUP BY card_value");
            $count = 0;
            foreach ($cards as $card) {
                if ($card["count"] > $count) {
                    $count = $card["count"];
                }
            }
            if ($count > $max_cards) {
                $max_cards = $count;
            }
        }
        return $max_cards;
    }

    /**
     * Calculates the stage of the game by determining the maximum number of cards held by any player
     * and updates the stage number in the "game_list" table.
     *
     * This function first calls the `calcStage()` method to calculate the maximum number of cards held
     * by any player in the game. It then updates the "stage_number" field in the "game_list" table
     * with the calculated stage number, using the join code of the current game instance as the
     * condition for the update.
     *
     * @return void
     */
    function checkStage() {
        $stage = $this->calcStage();
        $this->mysql->update("game_list", array("stage_number" => $stage), "join_code = '$this->id'");
    }

    /**
     * Retrieves the stage number from the "game_list" table based on the current game's join code.
     *
     * @return int The stage number from the "game_list" table.
     */
    function getStage() {
        return $this->mysql->select("game_list", "stage_number", "WHERE join_code = '$this->id'")[0]["stage_number"];
    }

    function getActivePlayers() {
        $players =  $this->mysql->select("game_$this->id", "*", "WHERE role = 'active'");
        $players2 = array();
        foreach ($players as $player) {
            $player["gamename"] = $this->getPlayerName($player["player_id"]);
            array_push($players2, $player);
        }
        return $players2;
    }

    function getDiscardCount($player_id) {
        return $this->mysql->select("cards_$this->id", "count(card_id) as count", "WHERE card_loc = 'discard_$player_id'")[0]["count"];
   }

   function getSpoonPlayers() {
    return $this->mysql->select("game_$this->id", "has_spoon");
   }

   function getPlayerID($user_id) {
    return $this->mysql->select("game_$this->id", "player_id", "WHERE user_id = '$user_id'")[0]["player_id"];
   }

   function getPlayerName($player_id){
    return $this->mysql->select("users", "gamename", "WHERE user_id = '" .$this->getUserID($player_id) . "'")[0]["gamename"];
   }

   function getUserID($player_id) {
    return $this->mysql->select("game_$this->id", "user_id", "WHERE player_id = '$player_id'")[0]["user_id"];
   }

    function deckToDiscard1() {
        return $this->mysql->update("cards_$this->id", array("card_loc" => "discard_1"), "card_loc = 'deck'");
    }

    function countMaxCards($player_id) {
        $cards = $this->mysql->select("cards_$this->id", "card_value, count(card_id) as count", "WHERE card_loc = 'hand_" . $player_id . "' GROUP BY card_value");
            $count = 0;
            foreach ($cards as $card) {
                if ($card["count"] > $count) {
                    $count = $card["count"];
                }
            }
            return $count;
    }
}