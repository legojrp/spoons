


$(document).ready(function() {

    $(document).on('click',".card", function() {
        var card_id = $(this).attr('card_id');
        sendCardToReplace(card_id); // Ensure this function is defined
    });

    

    sendRequest("full");
})