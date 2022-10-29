let messageId = "<?= $message->getId() ?>";
let userIsSender = "<?= $userIsSender ?>";

function deleteMessage() {
    let data = {
        _csrf: token,
        ajax: {
            id: messageId,
            isSender: userIsSender,
        }
    }
    $.ajax({
        url: '/profile/delete-message',
        method: 'get',
        cache: false,
        data: data,
        success: function (response) {
            console.log(response)
            //redirect
        }
    }) ;
}