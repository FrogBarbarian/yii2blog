const id = params.get('id');
document.getElementById('postApproveButton').onclick = () => {
    $.ajax({
        url: '/admin-ajax/approve-post',
        method: 'post',
        cache: false,
        data: {_csrf: token, id: id},
        success: function (response) {
            if (response === true) {
                location.href = '/';
            }

            notice('Ошибка', response)
        }
    });
}
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
    document.getElementById('disapproveButton').onclick = () => {
        const comment = document.getElementById('disapproveTextField').innerText;
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
