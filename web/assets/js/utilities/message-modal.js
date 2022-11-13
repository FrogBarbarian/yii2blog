/**
 * Получатель сообщения.
 * Тема сообщения.
 * Текст сообщения.
 */
let messageRecipient, messageSubject, messageContent;

/**
 * Создает модальное окно для написания сообщения пользователю.
 */
function createMessageModal(recipient = '', subject = '', content = '') {
    messageRecipient = recipient;
    messageSubject = subject;
    messageContent = content;
    $.ajax({
        url: '/message/create-modal-window',
        cache: false,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
}
