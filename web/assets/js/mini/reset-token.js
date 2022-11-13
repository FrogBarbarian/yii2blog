document.getElementById('sendResetTokenButton').onclick = () => {
    const form = $('#sendResetTokenForm');
    const formData = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        data: formData,
        success: function (response) {
            if (response === true) {
                $('#restoreWindowContent').html(
                    'Вам отправлено письмо со ссылкой для восстановления пароля.'
                );
            }

        }
    });
}
