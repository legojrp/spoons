<?php 
    require_once "../utils/creds.php";
    require_once "../utils/mysql.php";

    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);

    $username = $_POST['username'];
    $password = $_POST['password'];
    $gamename = $_POST['gamename'];


    $mysql->insert("users", ["username" => $username, "password" => $password, "gamename" => $gamename]);

    echo "success";
    


?>