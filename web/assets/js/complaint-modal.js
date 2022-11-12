/**
 * Создает модальное окно жалобы.
 * @param objectType Тип объекта.
 * @param objectId ID объекта.
 */
function createComplaint(objectType, objectId) {
    let data = {
        _csrf: token,
        objectType: objectType,
        objectId: objectId,
    };
    $.ajax({
        url: '/complaint/create-modal-window',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
}
