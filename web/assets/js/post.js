$(document).ready(function () {
    const postId = (new URL(document.location)).searchParams.get('id');
    const token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: postId,
        }
    };

    /**
     * Меняет правила комментирования поста.
     * Отрисовывает соответствующие элементы.
     */
    $('#commentsButton').click(function () {
        $.ajax({
            url: '/comments-u-i/comment-rule',
            cache: false,
            type: 'post',
            data: data,
            success: function (response) {
                if (response === true) {
                    $("#comments-permissions").html('');
                    $("#commentsButton").html(
                        "<img src='../../assets/images/comment-enabled.svg' alt='comment enabled' width='24'>"
                    );
                } else {
                    $("#comments-permissions").html(
                        "<div class='alert alert-danger text-center' role='alert'>" +
                        'Комментарии запрещены.' +
                        '</div>'
                    );
                    $("#commentsButton").html(
                        "<img src='../../assets/images/comment-disabled.svg' alt='comment disabled' width='24'>"
                    );
                }

            },
        });
    });

    /**
     * Ставит лайк посту.
     */
    $('#likePost').click(function () {
        $.ajax({
            url: '/post-u-i/like-post',
            cache: false,
            type: 'post',
            data: data,
            success: function () {
                updatePostRating(data);
                updateRatingButtons(data);
            },
        });
    });

    /**
     * Ставит дизлайк посту.
     */
    $('#dislikePost').click(function () {
        $.ajax({
            url: '/post-u-i/dislike-post',
            cache: false,
            type: 'post',
            data: data,
            success: function () {
                updatePostRating(data);
                updateRatingButtons(data);
            }
        });
    });

    setInterval(updatePostRating, 2000, data);
    setInterval(updateCommentsAmount, 2000, data);
});

/**
 * Обновляет рейтинг поста.
 */
function updatePostRating(data) {
    let curRating = document.getElementById('post-rating').textContent;
    data['ajax']['curRating'] = curRating;
    $.ajax({
        url: '/post-u-i/update-post-rating',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                $("#post-rating").html(response);
            }

            return false;
        }
    });
}

/**
 * Обновляет количество комментариев.
 */
function updateCommentsAmount(data) {
    let curCommentsAmount = parseInt(document.getElementById('commentsAmount').textContent);
    data['ajax']['curCommentsAmount'] = curCommentsAmount;
    $.ajax({
        url: '/comments-u-i/update-comments-amount',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                $("#commentsAmount").html(response);
            }

            return false;
        }
    })
}

/**
 * Обновляет цвет кнопок "лайк" и "дизлайк" к посту.
 */
function updateRatingButtons(data) {
    $.ajax({
        url: '/post-u-i/update-post-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            $likeButton = document.getElementById('likePost');
            $dislikeButton = document.getElementById('dislikePost');

            if (response[0] === true) {
                $likeButton.style.backgroundColor = 'green';
            } else {
                $likeButton.style.backgroundColor = '#f7f7f7';
            }

            if (response[1] === true) {
                $dislikeButton.style.backgroundColor = 'red';
            } else {
                $dislikeButton.style.backgroundColor = '#f7f7f7';
            }
        }
    });
}
