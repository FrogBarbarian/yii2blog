const token = $('meta[name=csrf-token]').attr("content");
const postId = (new URL(document.location)).searchParams.get('id');
let data = {
    _csrf: token,
    ajax: {
        postId: postId,
    }
};

$(document).ready(function () {
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

    setInterval(updatePostRating, 2000, data);
    setInterval(updateCommentsAmount, 2000, data);
});

/**
 * Ставит лайк посту.
 */
function likePost() {
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
}

/**
 * Ставит дизлайк посту.
 */
function dislikePost() {
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
}

/**
 * Обновляет рейтинг поста.
 */
function updatePostRating(data) {
    data['ajax']['curRating'] = document.getElementById('post-rating').textContent;
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
    data['ajax']['curCommentsAmount'] = parseInt(document.getElementById('commentsAmount').textContent);
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
            likeImg = document.getElementById('likePost');
            dislikeImg = document.getElementById('dislikePost');

            if (response[0] === true) {
                likeImg.src = '/assets/images/liked.svg';
            } else {
                likeImg.src = '/assets/images/like.svg';
            }

            if (response[1] === true) {
                dislikeImg.src = '/assets/images/disliked.svg';
            } else {
                dislikeImg.src = '/assets/images/dislike.svg';
            }
        }
    });
}

/**
 * Удаляет пост.
 */
function deletePost() {
    $.ajax({
        url: '/posts/delete-post',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            location.href = (response);
        }
    });
}
