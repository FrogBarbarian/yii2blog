/**
 * Удаляет жалобу.
 */
function closeComplaint(id) {
    $.ajax({
        url: '/admin-ajax/delete-complaint',
        cache: false,
        data: {id: id},
        success: function () {
            $('#complaint_' + id).html(
                '<small>' +
                'Жалоба удалена, сообщение пользователю отправлено' +
                '</small>' +
                '<hr>'
            );
        }
    });
}
