<?php 
require_once "utils/creds.php";
require_once "utils/mysql.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);




function initCards($game_code) {
    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);
    $mysql->createTable("cards_$game_code", "card_id int(11) NOT NULL AUTO_INCREMENT, card_loc varchar(255) NOT NULL, card_name varchar(255) NOT NULL, card_value int(11) NOT NULL, PRIMARY KEY (card_id)");
    
    $cards = json_decode(file_get_contents("cards.json"), true);
    foreach ($cards as $card) {
        $mysql->insert("cards_$game_code", array(
            "card_loc" => $card["location"],
            "card_name" => $card["name"],
            "card_value" => $card["value"]
        ));
    }
    
}

function shuffleCards($game_code) {
    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);
    $players = $mysql->select("cards_$game_code", "players");
    
    for ($i = 0; $i < count($players); $i++) {
        for ($j = 0; $j < 4; $j++) {
            $random_index = rand(0, 51);
            if ($mysql->select("cards_$game_code", "card_loc", "card_id = $random_index")[0]["card_loc"] == "deck") {
                $mysql->update("cards_$game_code", array("card_loc" => "hand_" . $players[$i]["player_id"]) , "card_id = $random_index");
            }
            else {
                $j -= 1;
            }
        }
    }
}


?>