<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spoons!</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #gameboard {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            width: 100%;
            height: 100%;
        }
        #pickup {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-end;
            height: 100%;
            width: 10%;
            background-color: #ccc;
        }
        #cards {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: flex-end;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="gameboard">
        <div id="pickup">
            <span>Pickup</span>
            <div id="cards"></div>
        </div>
    </div>
    <script>
        var interval = setInterval(function(){
            $.ajax({
                url: '../server/handler.php',
                data: {},
                type: 'GET',
                success: function(response) {
                    $('#cards').html(response);
                }
            });
        }, 1000);
    </script>
</body>
