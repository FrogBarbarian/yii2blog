let _messageData = document.getElementById('messageData');
let _messageId = _messageData.getAttribute('data-id');
let _userIsSender = _messageData.getAttribute('data-userIsSender') === '1';
let _messageSender = _messageData.getAttribute('data-sender');
let _messageRecipient = _messageData.getAttribute('data-recipient');
let _messageSubject = document.getElementById('messageSubject').innerText;
let _messageContent = document.getElementById('messageContent').innerHTML;

/**
 * Отрисовывает окно для отправки сообщения с данным для пересылки.
 */
let forwardButton = document.getElementById('forwardButton');
forwardButton.addEventListener('click', () => {
    _messageSubject = 'Fwd: ' + _messageSubject;
    _messageContent = "<br>" +
        "Пересланное сообщение от " +
        _messageSender +
        " для " +
        _messageRecipient +
        ":" +
        "<br>" +
        "<br>" +
        _messageContent;
    createMessageModal('', _messageSubject, _messageContent);
    _messageContent = document.getElementById('messageContent').innerText;
    _messageSubject = document.getElementById('messageSubject').innerHTML;
});

/**
 * Отрисовывает модальное окно с подтверждением на удаление.
 * Отправляет запрос на удаление сообщения.
 */
let deleteButton = document.getElementById('deleteButton');
deleteButton.addEventListener('click', () => {
    $('#modalDiv').html(
        '<div id="modalWindow" class="modal-window-back" tabindex="-1">' +
        '<div class="modal-window">' +
        '<div class="modal-window-header">' +
        'Удалить сообщение?' +
        '<button type="button" class="btn-close" onclick="closeModalDiv()">' +
        '</button>' +
        '</div>' +
        '<div class="modal-window-footer" style="justify-content: space-around !important;">' +
        '<button type="button" class="btn-basic" onclick="closeModalDiv()">' +
        'Отмена' +
        '</button>' +
        '<button id="confirmDelete" type="button" class="btn-basic">' +
        'Удалить' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    document.getElementById('confirmDelete').addEventListener('click', () => {
        let data = {
            _csrf: token,
            ajax: {
                id: _messageId,
                isSender: _userIsSender,
            }
        }
        $.ajax({
            url: '/profile/delete-message',
            method: 'post',
            cache: false,
            data: data,
            success: function (response) {
                location.href = response;
            }
        });
    });
});

let spamButton = document.getElementById('spamButton');

if (spamButton !== null) {
    //TODO: dodelat
    spamButton.addEventListener('click', () => {
        let data = {
            _csrf: token,
            ajax: {
                id: _messageId,
            }
        }
        $.ajax({
            url: '/profile/spam-message',
            method: 'post',
            cache: false,
            data: data,
            success: function (response) {
                location.href = response;
            }
        });
    });
}

let replyButton = document.getElementById('replyButton');

if (replyButton !== null) {
    /**
     * Отрисовывает окно для отправки сообщения с данным для ответа.
     */
    replyButton.addEventListener('click', () => {
        _messageRecipient = _messageSender;
        _messageSubject = 'RE: ' + _messageSubject;
        _messageContent = "<br>" +
            "<hr>" +
            _messageSender +
            " написал(а): " +
            "<br>" +
            _messageContent;
        createMessageModal(_messageRecipient, _messageSubject, _messageContent);
        _messageContent = document.getElementById('messageContent').innerHTML;
        _messageSubject = document.getElementById('messageSubject').innerText;
    });
}
