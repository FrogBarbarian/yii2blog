let setNewPasswordButton = document.getElementById('setNewPasswordButton');

if (setNewPasswordButton !== null) {
    setNewPasswordButton.onclick = () => {
        const form = $('#newPasswordForm');
        const formData = form.serialize();
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
