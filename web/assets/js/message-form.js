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
            if (response === true) {
                closeModalDiv();
                alert('Сообщение успешно отправлено.')

                return true;
            }

            let errorLabels = form[0].querySelectorAll('[id$=ErrorLabel]');

            for (const errorLabel of errorLabels) {
                let field = errorLabel.id.slice(0, -10);

                if (field in response) {
                    errorLabel.innerHTML = response[field][0];
                    continue;
                }

                errorLabel.innerHTML = '';
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
