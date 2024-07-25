<?php 

require_once "../utils/creds.php";
require_once "../utils/mysql.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);


$tables = $mysql->select("information_schema.tables", "table_name", "WHERE table_schema = '" . "spoons" . "' AND table_name NOT IN ('game_list', 'users')");

foreach ($tables as $table) {
    if (isset($table["TABLE_NAME"]))
        $mysql->query("DROP TABLE " . $table["TABLE_NAME"], false);
}
