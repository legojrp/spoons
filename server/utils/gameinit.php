<?php 
    require_once "creds.php";
    require_once "mysql.php";
    require_once "gameutils.php";

    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);

    $player_id = $_COOKIE["player_id"];
    $id = idmaker();
    $mysql->insert("game_list", array("join_code" => $id, "active" => "1"));

    $mysql->createTable("game_$id", "players varchar(255) NOT NULL, player_id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (player_id)");

    $gameutils = new GameUtils($id);
    $gameutils->playerJoined($player_id);


    echo $id;

    function idmaker() {
        $date = date('dmyyHis');
        $hash = hash("sha256", $date);
        return substr($hash, 0, 6);
    }



