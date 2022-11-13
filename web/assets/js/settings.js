/**
 * Заголовок для окна оповещений при изменении настроек.
 */
let noticeHeader = 'Настройки применены';

/**
 * Переключатель видимости профиля.
 */
let visibilitySwitcher = document.getElementById('profileVisibility');
visibilitySwitcher.onchange = () => {
    let data = {
        _csrf: token,
        isVisible: visibilitySwitcher.checked,
    };
    $.ajax({
        url: '/settings/change-visibility',
        type: 'post',
        data: data,
        success: function () {
            notice(noticeHeader,
                'Профиль ' +
                (visibilitySwitcher.checked ? 'открыт' : 'скрыт')
            );
        }
    });
}

/**
 * Переключатель открытости сообщений.
 */
let messagesSwitcher = document.getElementById('messagesStatus');
messagesSwitcher.onchange = () => {
    let data = {
        _csrf: token,
        isOpen: messagesSwitcher.checked,
    };
    $.ajax({
        url: '/settings/open-close-messages',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            notice(noticeHeader,
                'Личные сообщения ' +
                (messagesSwitcher.checked ? 'открыты' : 'закрыты')
            );
        }
    });
}

/**
 * Кнопка отрисовки модального окна для смены пароля.
 */
let createPasswordModalButton = document.getElementById('createPasswordModalButton');
document.getElementById('createPasswordModalButton').onclick = () => {
    $.ajax({
        url: '/settings/create-password-modal-window',
        cache: false,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
}

/**
 * Кнопка отрисовки модального окна для смены почты.
 */
let createEmailModalButton = document.getElementById('createEmailModalButton');
document.getElementById('createEmailModalButton').onclick = () => {
    $.ajax({
        url: '/settings/create-email-modal-window',
        cache: false,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
}
