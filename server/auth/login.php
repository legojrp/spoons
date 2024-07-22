<?php
require_once "../utils/creds.php";
require_once "../utils/mysql.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);

$username = $_POST['username'];
$password = $_POST['password'];

require_once "common.php";


