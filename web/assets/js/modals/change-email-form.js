/**
 * Кнопка отправки формы.
 */
let changeEmailButton;

setupEmailFormJsData()

/**
 * Изменение почты.
 */
function changeEmail() {
    let form = $('#changeEmailForm')
    let formData = form.serialize()
    $.ajax({
        url: form.attr('action'),
        cache: false,
        type: form.attr('method'),
        data: formData,
        success: function (response) {
            if (response === true) {
                notice(noticeHeader,'Email изменен');
                closeModalDiv();
            }

            shakeModal();
        }
    });
}

/**
 * Записывает данные с модального окна.
 */
function setupEmailFormJsData() {
    changeEmailButton = document.getElementById('changeEmailButton');
    changeEmailButton.addEventListener('click', changeEmail);
}
