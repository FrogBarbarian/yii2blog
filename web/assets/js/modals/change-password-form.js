/**
 * Кнопка отправки формы.
 */
let changePasswordButton;

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
                notice('Пароль изменен');
                closeModalDiv();
            }

            shakeModal();
        }
    });
}

function setupPasswordFormJsData() {
    changePasswordButton = document.getElementById('changePasswordButton');
    changePasswordButton.addEventListener('click', changePassword);
    passwordFields = document.querySelectorAll('[type=password]');
    togglePasswordButton = document.getElementById('togglePasswordButton');
    togglePasswordButton.addEventListener('click', showingPassword);
}
