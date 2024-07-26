<?php 
require_once "utils/gameutils.php";
require_once "utils/creds.php";
require_once "utils/mysql.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);

$join_code = getGameFromPlayer($_COOKIE["player_id"]);
$gameutils = new GameUtils($join_code);
if (!$gameutils->isPlayerInGame($_COOKIE["player_id"])) {
    
    $action = "out";
    echo json_encode(array("action" => $action, "game_code" => $join_code));
    exit();
}
if ($gameutils->getNumPlayers() == 1) {
    if ($gameutils->isPlayerInGame($_COOKIE["player_id"])) {
        $action = "win";
        $gameutils->endGame();
        echo json_encode(array("action" => $action, "game_code" => $join_code));
        exit();

    }
}



if (!isset($_POST["action"]) || !isset($_POST["type"])) {
    echo "error";
}

$information_number = $_POST["information"];
$action = $_POST["action"];
$type = $_POST["type"];

$player_id = $gameutils->getPlayerID($_COOKIE["player_id"]);

global $gameutils, $player_id;

$actionDone = doAction($action, $player_id, $type, $gameutils);

$data = makeRequest($player_id, $type, $gameutils, $action, $actionDone);

detectRoundOver($gameutils);

$response = array();
$response["data"] = $data;
$response["action"] = $actionDone;
$response["type"] = $type;
$response["information"] = $information_number;
$response["game_code"] = $join_code;
echo json_encode($response);

function doAction($action, $player_id, $type, $gameutils) {
    if ($action == "request") {
        return "request";
    }
    
    if ($action == "card replace") {
        $card_id = $_POST["data"]["card_id"];
        if ($gameutils->getDiscardCount($player_id) == 0) {
            return "no more cards to replace";
        }
        $gameutils->cycle($player_id, $card_id);
        return "card replace";
    }

    if ($action == "spoon take") {
        if ($gameutils->countMaxCards($player_id) == 4 || $gameutils->getSpoonCount($player_id) < $gameutils->getNumPlayers() - 1) {
            $gameutils->takeSpoon($player_id);
            return "spoon take";
        }
        else {
            return "no spoon or not enough cards";
        }
    }

    return "error";
}

function makeRequest($player_id, $type, $gameutils, $action, $actionDone) {
    $data = array();
    if ($type == "full") {
        // send cards

        $cards = $gameutils->getCards($player_id);
        $data["cards"] = $cards;

        // send spoons
        $data["spoons_number"] = $gameutils->getSpoonCount($player_id);
        
        $players = $gameutils->getActivePlayers();
        $data["players"] = $players;

        // discard number
        $data["discard_number"] = $gameutils->getDiscardCount($player_id);

        // stage 

        $data["stage"] = $gameutils->getStage();

        // future stuff

        return $data;
    }

    if ($type == "half") {
        // send cards
        $cards = $gameutils->getCards($player_id);
        $data["cards"] = $cards;

        // send spoons
        $data["spoons_number"] = $gameutils->getSpoonCount($player_id);

        $players = $gameutils->getSpoonPlayers();

        $data["players"] = $players;

        // discard number    

        $data["discard_number"] = $gameutils->getDiscardCount($player_id);

        // stage

        $data["stage"] = $gameutils->getStage();

        // future stuff

        return $data;

    }

    if ($type == "mini") {
        // send spoons
        $data["spoons_number"] = $gameutils->getSpoonCount($player_id);

        $players = $gameutils->getSpoonPlayers();
        $data["players"] = $players;

        // stage

        $data["stage"] = $gameutils->getStage();

        return $data;
        // future stuff

    }
    $data["error"] = "error";  

    return $data;

}

function getGameFromPlayer($player_id) {
    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);

    return $mysql->select("users", "in_game", " WHERE user_id = '" . $player_id . "'")[0]["in_game"];
}

function detectRoundOver($gameutils) {
    if ($gameutils->getSpoonCount() == 0) {
        $gameutils->nextRound();
    }

}