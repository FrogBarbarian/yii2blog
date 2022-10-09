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
            url: '/post-interface/like-post',
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
            url: '/post-interface/dislike-post',
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
        url: "/post-interface/update-post-rating",
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
        url: '/post-interface/update-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response[0] === true) {
                document.getElementById('like').style.backgroundColor = 'green';
            } else {
                document.getElementById('like').style.backgroundColor = 'grey';
            }

            if (response[1] === true) {
                document.getElementById('dislike').style.backgroundColor = 'red';
            } else {
                document.getElementById('dislike').style.backgroundColor = 'grey';
            }
        }
    });
}
