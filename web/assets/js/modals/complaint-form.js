/**
 * Div контейнер для ввода текста жалобы.
 * Скрытый input для передачи текста жалобы.
 */
let complaintInputField, complaintHiddenInput;

/**
 * Записывает данные с модального окна.
 */
function setComplaintFormData() {
    complaintInputField = document.getElementById('complaintInputField');
    complaintHiddenInput = document.getElementById('complaintHiddenInput');
    complaintInputField.oninput = () => {
        complaintHiddenInput.value = complaintInputField.innerText;
    }
    const blur = new Event('blur');
    complaintInputField.onblur = () => {
        complaintHiddenInput.dispatchEvent(blur);
    }
}

/**
 * Отправляет жалобу.
 */
function sendComplaint() {
    let form = $('#complaintForm');
    let formData = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        data: formData,
        success: function (response) {
            if (response === true) {
                closeModalDiv();
                notice('Жалоба успешно отправлена', '');

                return true;
            }

            shakeModal();
        }
    });
}
