/**
 * Кнопка отправки формы.
 */
let changeEmailButton;
setupEmailFormJsData()

function changeEmail() {
    let form = $('#changeEmailForm')
    let formData = form.serialize()
    $.ajax({
        url: form.attr('action'),
        cache: false,
        type: 'post',
        data: formData,
        success: function (response) {
            if (response === true) {
                notice('Email изменен');
                closeModalDiv();
            }

            shakeModal();
        }
    });
}

function setupEmailFormJsData() {
    changeEmailButton = document.getElementById('changeEmailButton');
    changeEmailButton.addEventListener('click', changeEmail);
}
