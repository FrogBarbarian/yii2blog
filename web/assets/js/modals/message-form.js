let contentInputField, contentHiddenInput, recipientInputField, subjectInputField;

function setMessageFormData() {
    contentInputField = document.getElementById('contentInputField');
    contentHiddenInput = document.getElementById('contentHiddenInput');
    recipientInputField = document.getElementById('recipientUsername');
    subjectInputField = document.getElementById('subjectInputField');
    recipientInputField.value = messageRecipient;
    subjectInputField.value = messageSubject;
    contentInputField.innerHTML = messageContent;
    contentHiddenInput.value = contentInputField.innerText;
    recipientInputField.oninput = () => {
        getRecipients(recipientInputField.value);
    }
    contentInputField.oninput = () => {
        contentHiddenInput.value = contentInputField.innerText;
    }
    const blur = new Event('blur');
    contentInputField.onblur = () => {
        contentHiddenInput.dispatchEvent(blur)
    }
}

/**
 * Отправляет сообщение.
 */
function sendMessage() {
    let form = $('#messageForm');
    let formData = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        data: formData,
        success: function (response) {
            alert(1)
            if (response === true) {
                closeModalDiv();
                notice('Сообщение отправлено','');

                return true;
            }
        }
    });
}

/**
 * Вставляет имя пользователя в поле получателя.
 * @param {String} username
 */
function addRecipient(username) {
    $('#suggestedRecipients').html('');
    recipientInputField.value = username;
}

/**
 * Получает список имен пользователей и заполняет соответствующее поле.
 * @param {String} data
 */
function getRecipients(data) {
    let field = $('#suggestedRecipients');

    if (data === '') {
        field.html('');

        return false;
    }

    $.ajax({
        url: '/u-i/get-users',
        cache: false,
        data: {data: data},
        success: function (response) {
            field.html('');

            if (response === false) {
                return false;
            }

            response.forEach((user) => {
                field.html(field.html() +
                    '<li class="message-suggested-user" onclick="addRecipient(\'' +
                    user['username'] +
                    '\')">' +
                    user['username'] +
                    '</li>'
                );
            });
        }
    })
}
