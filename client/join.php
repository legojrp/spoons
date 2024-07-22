<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Game</title>
</head>
<body>
<input type="text" id="join_code">
<button onclick="window.location.href = '../server/lobby/join.php?join_code=' + document.getElementById('join_code').value;">Join</button>
</body>
</html>