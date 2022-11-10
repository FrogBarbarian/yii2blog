const noticeHeader = 'Настройки применены';
/**
 * Переключатель видимости профиля.
 */
const visibilitySwitcher = document.getElementById('profileVisibility');
visibilitySwitcher.addEventListener('change', () => {
    let data = {
        _csrf: token,
        ajax: {
            isVisible: visibilitySwitcher.checked,
        },
    };
    $.ajax({
        url: '/settings/change-visibility',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            notice(noticeHeader,
                'Профиль ' +
                (visibilitySwitcher.checked ? 'открыт' : 'скрыт')
            );
        }
    });
});

/**
 * Переключатель открытости сообщений.
 */
const messagesSwitcher = document.getElementById('messagesStatus');
messagesSwitcher.addEventListener('change', () => {
    let data = {
        _csrf: token,
        ajax: {
            isOpen: messagesSwitcher.checked,
        },
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
});

/**
 * Кнопка отрисовки модального окна для смены пароля.
 */
const createPasswordModalButton = document.getElementById('createPasswordModalButton');
createPasswordModalButton.addEventListener('click', () => {
    $.ajax({
        url: '/settings/create-password-modal',
        cache: false,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
});

/**
 * Кнопка отрисовки модального окна для смены почты.
 */
const createEmailModalButton = document.getElementById('createEmailModalButton');
createEmailModalButton.addEventListener('click', () => {
    $.ajax({
        url: '/settings/create-email-modal',
        cache: false,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
});
