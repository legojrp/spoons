function setCards (data){
    $(".cards").empty();
    for (var i = 0; i < data.length; i++) {
        var card = data[i];
        var card_div = $("<div>").addClass("card");
        card_div.attr("card_id", card["card_id"]);
        var card_img = $("<img>").attr("src", "../resources/SVG-cards-1.3/" + filename(card["card_name"]) + ".svg");
        card_div.append(card_img);
        $(".cards").append(card_div);
    }
    
}

function setSpoonCount (spoons_number){
    var spoons = "";

    for (var i = 0; i < spoons_number; i++) {
        spoons += "🥄";
    }

    $("#spoon-number").text(spoons);
}

function setDiscardCount (number){
    $("#discard-number").text(number);
}

function setPlayerSet(data){
    $(".players").empty();
    for (var i = 0; i < data.length; i++) {
        var player = data[i];
        var player_div = $("<div>").addClass("player");
        var player_name = $("<span>").addClass("player-name").attr("id", "name-player-" + player["player_id"]);
        var player_spoon = $("<span>").addClass("player-spoon").attr("id", "spoon-player-" + player["player_id"]);
        
        player_name.text(player["gamename"]);
        player_spoon.text(player["has_spoon"] == 1 ? "🥄" : "⊖");
        
        player_div.append(player_name);
        player_div.append(player_spoon);

        if (player["is_you"]) {
            player_div.addClass("you");
            player_name.css("color", "purple");
        }
        
        $(".players").append(player_div);
    }
}

function setPlayerSpoons (data){
    for (var i = 0; i < data.length; i++) {
        var player = data[i];
        var player_spoon = $("#spoon-player-" + player["player_id"]);
        player_spoon.text(player["has_spoon"] == 1 ? "🥄" : "⊖");
    }
}

function setStage (data){

}

function setGameCode (data){

}

function information (data){

}


function filename (name) {
    name = name.replace(/ /g, "_");
    name = name.toLowerCase();
    if (!(/^[0-9ace]/i.test(name.charAt(0)))) {
        name = name + "2";
    }

    return name;
}