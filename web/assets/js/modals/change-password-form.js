/**
 * Кнопка отправки формы.
 */
let changePasswordButton;

/**
 * Изменения пароля
 */
function changePassword() {
    let form = $('#changePasswordForm')
    let formData = form.serialize()
    $.ajax({
        url: form.attr('action'),
        cache: false,
        type: 'post',
        data: formData,
        success: function (response) {
            if (response === true) {
                notice(noticeHeader,'Пароль изменен');
                closeModalDiv();
            }

            shakeModal();
        }
    });
}

/**
 * Записывает данные с модального окна.
 */
function setupPasswordFormJsData() {
    changePasswordButton = document.getElementById('changePasswordButton');
    changePasswordButton.addEventListener('click', changePassword);
    passwordFields = document.querySelectorAll('[type=password]');
    togglePasswordButton = document.getElementById('togglePasswordButton');
    togglePasswordButton.addEventListener('click', showingPassword);
}
