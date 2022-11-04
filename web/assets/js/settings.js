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
        data: data
    });
});

/**
 * Кнопка отрисовки модального окна для смены пароля.
 */
const createPasswordModalButton = document.getElementById('createPasswordModal');
createPasswordModalButton.addEventListener('click', () => {
    $.ajax({
        url: '/settings/create-password-modal',
        cache: false,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
});
