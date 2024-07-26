<?php 
    require_once "../../server/utils/creds.php";
    require_once "../../server/utils/mysql.php";
    require_once "../../server/utils/gameutils.php";

    $player_id = $_COOKIE["player_id"];
    $join_code = $_GET["join_code"];

    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);

    $gameutils = new GameUtils($join_code);

    if($gameutils->isHost($player_id) && !$gameutils->isActive()) {
        $gameutils->startGame();
        $gameutils->shuffleCards();
    }

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spoons Card Game</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <script src="client.js"></script>
    <script src="dataProcess.js"></script>
    <script src="listeners.js"></script>
</head>
<body>
    <div class="game-container">
        <div class="players">
            <div class="player">
                <span class="player-name" id="name-player-1">Player 1</span>
                <span class="player-spoon" id="spoon-player-1">🥄</span>
            </div>
            
            <!-- Add more players as needed -->
        </div>

        <div class="table-spoons">
            <span id="spoon-number">🥄🥄🥄</span>
        </div>

        <div class="cards-container">
            <div class="cards">
                <div class="card">A</div>
                <div class="card">K</div>
                <div class="card">Q</div>
                <div class="card">J</div>
                <div class="card" >J</div>
            </div>
            <div class="pickup-pile">
                <div class="pickup-pile-overlay" id="discard-number">12</div>
            </div>
        </div>
    </div>
</body>
</html>
