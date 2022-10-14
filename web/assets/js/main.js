/**
 * Открывает страницу с рандомным постом.
 */
function randomPost() {$.ajax({
            url: '/u-i/random-post',
            success: function (response) {
                location.href = (response);
            },
        });
}

/**
 * Скроллит страницу на самый верх.
 */
function goTop() {
    window.scrollTo(0, 0);
}

/**
 * Прячет и показывает стрелку для прокрутки страницы вверх.
 */
window.addEventListener('scroll', function () {
    arrowTop.hidden = (pageYOffset < (document.documentElement.clientHeight / 2));
})

/**
 * Создает окно жалобы.
 * @param objectType Тип объекта.
 * @param objectId ID объекта.
 * @param subjectId  ID отправителя.
 */
function createComplaint(objectType, objectId, senderId) {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        ajax: {
            objectType: objectType,
            objectId: objectId,
            senderId: senderId,
        },
    };
    $.ajax({
        url: '/u-i/create-complaint-window',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            $('#complaintZone').html(response);
        }
    });
}

/**
 * Закрывает окно жалобы.
 */
function closeComplaintWindow() {
    $('#complaintZone').html('');
}

/**
 * Ловит нажатие кнопки Esc при открытом окне жалобы.
 */
window.onkeyup = function (e) {
    var elementExists = document.getElementById("complaintWindow");
    if (elementExists !== null && e.keyCode == 27) closeComplaintWindow();
}
