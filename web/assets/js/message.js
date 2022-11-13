/**
 * Данные сообщения.
 */
let _messageData = document.getElementById('messageData');
/**
 *  ID сообщения.
 */
let _messageId = _messageData.getAttribute('data-id');

/**
 * Является ли пользователь отправителем.
 */
let _userIsSender = _messageData.getAttribute('data-userIsSender') === '1';

/**
 * Отправитель письма.
 */
let _messageSender = _messageData.getAttribute('data-sender');

/**
 * Получатель письма.
 */
let _messageRecipient = _messageData.getAttribute('data-recipient');

/**
 * Тема письма.
 */
let _messageSubject = document.getElementById('messageSubject').innerText;

/**
 * Текст письма.
 */
let _messageContent = document.getElementById('messageContent').innerHTML;

/**
 * Кнопка ответа на письмо.
 */
let replyButton = document.getElementById('replyButton');

/**
 * Кнопка пересылки письма.
 */
let forwardButton = document.getElementById('forwardButton');

/**
 * Кнопка отправки сообщения в(из) спам(а).
 */
let spamButton = document.getElementById('spamButton');

if (forwardButton !== null) {
    /**
     * Отрисовывает окно для отправки сообщения с данным для пересылки.
     */
    forwardButton.onclick = () => {
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
    }
}

/**
 * Отрисовывает модальное окно с подтверждением на удаление.
 * Отправляет запрос на удаление сообщения.
 */
let deleteButton = document.getElementById('deleteButton').onclick = () => {
    $('#modalDiv').html(
        '<div id="modalWindow" class="modal-window-back" tabindex="-1">' +
        '<div class="modal-window">' +
        '<div class="modal-window-header">' +
        'Удалить сообщение?' +
        '<button type="button" class="btn-close" onclick="closeModalDiv()">' +
        '</button>' +
        '</div>' +
        '<div class="modal-window-footer justify-content-around">' +
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

    /**
     * Подтверждение удаления письма.
     */
    document.getElementById('confirmDelete').onclick = () => {
        let data = {
            _csrf: token,
            id: _messageId,
            isSender: _userIsSender,
        }
        $.ajax({
            url: '/message/delete',
            method: 'post',
            data: data,
            success: function (response) {
                location.href = response;
            }
        });
    }
}

if (spamButton !== null) {
    /**
     * Отправляет сообщение в(из) спам(а).
     */
    spamButton.onclick = () => {
        let data = {
            _csrf: token,
            id: _messageId,
        }
        $.ajax({
            url: '/message/spam',
            method: 'post',
            data: data,
            success: function (response) {
                location.href = response;
            }
        });
    }
}

if (replyButton !== null) {
    /**
     * Отрисовывает окно для отправки сообщения с данным для ответа.
     */
    replyButton.onclick = () => {
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
    }
}
