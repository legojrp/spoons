function processResponse (response) {
    // TODO
    response = JSON.parse(response);
    if (response.error != "none"){
        alert(response.error);
        return;
    }

    if (response.type == "full") {
        setCards(response.data.cards);
        setSpoonCount(response.data.spoons_number);
        setActivePlayers(response.data.players);
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
    // TODO
}

function sendPageLoad () {
    // TODO
}

function sendSpoonTake () {
    // TODO
}



