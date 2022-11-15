/**
 * ID поста.
 */
const id = params.get('id');

/**
 * Утверждает пост.
 */
document.getElementById('postApproveButton').onclick = () => {
    $.ajax({
        url: '/admin-ajax/approve-post',
        method: 'post',
        cache: false,
        data: {_csrf: token, id: id},
        success: function (response) {
            if (response === true) {
                location.href = '/';

                return true;
            }

            notice('Ошибка', response)
        }
    });
}

/**
 * Рисует модальное окно для отказа в утверждении поста.
 */
document.getElementById('postDisapproveButton').onclick = () => {
    $('#modalDiv').html(
        '<div id="modalWindow" class="modal-window-back">' +
        '<div class="modal-window">' +
        '<div class="modal-window-header">' +
        'Комментарий к отказу' +
        '<button class="btn-close" onclick="closeModalDiv()">' +
        '</button>' +
        '</div>' +
        '<div class="div-input-basic" id="disapproveTextField" contenteditable="true">' +
        '</div>' +
        '<div class="modal-window-footer">' +
        '<button class="btn-basic" onclick="closeModalDiv()">' +
        'Отмена' +
        '</button>' +
        '<button id="disapproveButton" class="btn-basic">' +
        'Отказать' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>'
    );

    /**
     * Отказ в утверждении поста.
     */
    document.getElementById('disapproveButton').onclick = () => {
        let comment = document.getElementById('disapproveTextField').innerText;
        $.ajax({
            url: '/admin-ajax/disapprove-post',
            method: 'post',
            cache: false,
            data: {_csrf: token, comment: comment, id: id},
            success: function () {
                location.href = '/';
            }
        });
    }
}
