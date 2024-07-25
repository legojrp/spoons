<?php 
    require_once "../utils/creds.php";
    require_once "../utils/mysql.php";

    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);

    $id = $_GET["join_code"];

    if (isset($mysql->select("game_list", "active", "WHERE join_code = '$id' AND active = '1'")[0])) {
        echo "true";
    } else {
        echo "false";
    }
