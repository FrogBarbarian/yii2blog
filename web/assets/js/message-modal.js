let messageRecipient, messageSubject, messageContent;

/**
 * Создает модальное окно для написания сообщения пользователю.
 */
function createMessageModal(recipient = '', subject = '', content = '') {
    messageRecipient = recipient;
    messageSubject = subject;
    messageContent = content;
    $.ajax({
        url: '/u-i/message-modal',
        cache: false,
        type: 'post',
        data: {_csrf: token},
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
}
