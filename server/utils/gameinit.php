<?php 
    require_once "creds.php";
    require_once "mysql.php";
    require_once "gameutils.php";

    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);

    $player_id = $_COOKIE["player_id"];
    $id = idmaker();
    $mysql->insert("game_list", array("join_code" => $id, "active" => "0", "creator_user_id" => $player_id));

    $mysql->createTable("game_$id", "user_id varchar(255) NOT NULL, player_id int(11) NOT NULL AUTO_INCREMENT, has_spoon BOOLEAN DEFAULT 0, role VARCHAR(255) DEFAULT 'active', PRIMARY KEY (player_id)");

    $gameutils = new GameUtils($id);
    $gameutils->playerJoined($player_id);
    echo $id;

    function idmaker() {
        $date = date('dmyyHis');
        $hash = hash("sha256", $date);
        return substr($hash, 0, 6);
    }



