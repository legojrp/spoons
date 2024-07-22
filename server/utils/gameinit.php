<?php 

    $id = idmaker();
    $mysql->insert("game_list", array("join_code" => $id, "active" => "1"));
    $mysql->createTable("game_$id", "players varchar(255) NOT NULL, player_id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (player_id)");

    echo $id;   