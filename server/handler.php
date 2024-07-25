<?php 
require_once "utils/gameutils.php";
require_once "utils/creds.php";
require_once "utils/mysql.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);

$join_code = $_GET["join_code"];
$gameutils = new GameUtils($join_code);


if (!isset($_POST["action"]) || !isset($_POST["type"])) {
    echo "error";
}

$information_number = $_POST["information"];
$action = $_POST["action"];
$type = $_POST["request_type"];

$player_id = $_COOKIE["player_id"];

global $gameutils, $player_id;

$actionDone = doAction($action, $player_id, $information_number, $type, $gameutils);

$data = makeRequest($player_id, $type, $gameutils, $action, $actionDone);

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
        $gameutils->cycle($player_id, $card_id);
        return "card replace";
    }

    if ($action == "spoon take") {
        $gameutils->takeSpoon($player_id);
        return "spoon take";
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
