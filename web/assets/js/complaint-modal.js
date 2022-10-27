/**
 * Поле ввода жалобы.
 */
let complaintInputField = document.getElementById('complaintInputField');
/**
 * Скрытое поле для текста жалобы.
 */
let complaintHiddenInput = document.getElementById('complaintHiddenInput');
complaintInputField.oninput = () => {
    complaintHiddenInput.value = complaintInputField.innerText;
}

function rewriteData() {
    complaintInputField = document.getElementById('complaintInputField');
    complaintHiddenInput = document.getElementById('complaintHiddenInput');
    complaintInputField.oninput = () => {
        complaintHiddenInput.value = complaintInputField.innerText;
    }
}

function sendComplaint() {
    let form = $('#complaint-form');
    let formData = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        data: formData,
        success: function (response) {
            if (response === true) {
                closeModalDiv();
                alert('Жалоба успешно отправлена.');
            } else {
                $('#complaintErrorLabel').html(response['complaint'][0]);
            }
        }
    });
}
