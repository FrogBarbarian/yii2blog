$(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: $('#postId').val()
        }
    };
    /**
     * Ставит лайк посту.
     */
    $('#like').click(function () {
        $.ajax({
            url: '/u-i/like-post',
            cache: false,
            type: 'post',
            data: data,
            success: function () {
                updateRating(data);
                updateRatingButtons(data);
            },
        });
    });
    /**
     * Ставит дизлайк посту.
     */
    $('#dislike').click(function () {
        $.ajax({
            url: '/u-i/dislike-post',
            cache: false,
            type: 'post',
            data: data,
            success: function () {
                updateRating(data);
                updateRatingButtons(data);
            }
        });
    });
    updateRating(data);
    setInterval('updateRating', 2000, data);
});

/**
 * Обновляет рейтинг поста.
 */
function updateRating(data) {
    $.ajax({
        url: "/u-i/update-post-rating",
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            $("#rating-container").html(html);
        }
    });
}

/**
 * Обновляет цвет кнопок "лайк" и "дизлайк".
 */
function updateRatingButtons(data) {
    $.ajax({
        url: '/u-i/update-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response[0] === true) {
                document.getElementById('like').style.backgroundColor = 'green';
            } else {
                document.getElementById('like').style.backgroundColor = '#f7f7f7';
            }

            if (response[1] === true) {
                document.getElementById('dislike').style.backgroundColor = 'red';
            } else {
                document.getElementById('dislike').style.backgroundColor = '#f7f7f7';
            }
        }
    });
}

/**
 * Создает окно жалобы.
 * @param objectType Тип объекта.
 * @param objectId ID объекта.
 * @param subjectId  ID отправителя.
 */
function createComplaint(objectType, objectId, subjectId) {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        ajax: {
            objectType: objectType,
            objectId: objectId,
            subjectId: subjectId,
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
    })
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
window.onkeyup = function(e) {
    var elementExists = document.getElementById("complaintWindow");
    if (elementExists !== null && e.keyCode == 27) closeComplaintWindow();
}
