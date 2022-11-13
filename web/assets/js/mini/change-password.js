/**
 * Кнопка для отправки нового пароля.
 */
let setNewPasswordButton = document.getElementById('setNewPasswordButton');

if (setNewPasswordButton !== null) {
    /**
     * Отправка формы с новым паролем.
     */
    setNewPasswordButton.onclick = () => {
        let form = $('#newPasswordForm');
        let formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            cache: false,
            data: formData,
            success: function (response) {
                if (response === true) {
                    $('#newPasswordWindowContent').html(
                        'Пароль успешно изменен.'
                    );
                }
            }
        });
    }
}
