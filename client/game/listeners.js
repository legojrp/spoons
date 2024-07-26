


$(document).ready(function() {

    $(document).on('click',".card", function() {
        var card_id = $(this).attr('card_id');
        sendCardToReplace(card_id); // Ensure this function is defined
    });

    $(document).on('click',".table-spoons", function() {
        sendSpoonTake();
    });
    

    sendRequest("full");
})


setInterval(function() {
    sendRequest("full");
}, 1000);
