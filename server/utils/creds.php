<?php 
class Creds {
    public $host = "127.0.0.1";
    public $user = "phpguy";
    public $password = "Flypigsfly1";
    public $database = "spoons";

    public function __construct($database = "spoons") {
        $this->database = $database;
    }
}