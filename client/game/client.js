function processResponse (response) {
    // TODO
    if (!typeof response === 'string' || !response.startsWith('{') && response.endsWith('}')){
        console.error(response);
        return;
    }

    response = JSON.parse(response);
    // if (response.error != "none"){
    //     alert(response.error);
    //     return;
    // }

    if (response.action == "out"){
        window.location.href = './spectate.php?join_code=' + response.game_code;
    }

    if (response.action == "win"){
        window.location.href = './win.php?join_code=' + response.game_code;
    }

    if (response.type == "full") {
        setCards(response.data.cards);
        setSpoonCount(response.data.spoons_number);
        setPlayerSet(response.data.players);
        setDiscardCount(response.data.discard_number);
        setStage(response.data.stage);
    }

    if (response.type == "half") {
        setCards(response.data.cards);
        setSpoonCount(response.data.spoons_number);
        setDiscardCount(response.data.discard_number);
        setPlayerSpoons(response.data.spoons_number);
        setStage(response.data.stage);
    }

    if (response.type == "mini") {
        setSpoonCount(response.data.spoons_number);
        setPlayerSpoons(response.data.spoons_number);
        setStage(response.data.stage);
    }

}


function sendCardToReplace (card_id) {
    request = {
        type: "full",
        action: "card replace",
        information: 1,
        data: {
            card_id: card_id
        }
    }
    send(request);
}

function sendPageLoad () {
    // TODO
}

function sendSpoonTake () {
    request = {
        type: "full",
        action: "spoon take",
        information: 1
    }

    send(request);
}

function sendRequest (type){
    request = {
        type: type,
        action: "request",
        information: 1
    }

    send(request);
 }

 function send(request) {
    $.ajax({
        url: "../../server/handler.php",
        type: "POST",
        data: request,
        success: function (response) {
            processResponse(response);
        }
        
    });
 }




