/**
 * ID поста.
 */
const postId = params.get('id');

let data = {
    _csrf: token,
    postId: postId,
};

$(document).ready(function () {
    /**
     * Меняет правила комментирования поста.
     * Отрисовывает соответствующие элементы.
     */
    $('#commentsButton').click(function () {
        $.ajax({
            url: '/post-ajax/comment-permissions',
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
    setInterval(updateComments, 2000);
});

/**
 * Ставит лайк/дизлайк посту.
 */
function likeOrDislikePost(isLike) {
    data['isLike'] = isLike;
    $.ajax({
        url: '/post-ajax/like-or-dislike',
        type: 'post',
        data: data,
        success: function (response) {
            updatePostRating(data);
            updateRatingButtons();
        }
    });
}

/**
 * Обновляет рейтинг поста.
 */
function updatePostRating(data) {
    data['curRating'] = document.getElementById('post-rating').textContent;
    $.ajax({
        url: '/post-ajax/update-post-rating',
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
 * Обновляет комментарии к посту.
 */
function updateComments() {
    let data = {
        _csrf: token,
        postId: postId,
        curCommentsAmount: comments.children('li').length,
    }
    $.ajax({
        url: '/post-ajax/update-comments',
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                let html = comments.html();
                comments.html(html + response['comments']);
                $("#commentsAmount").html(response['amount']);
            }

            updateCommentsRating();
        }
    });
}

/**
 * Обновляет цвет кнопок "лайк" и "дизлайк" к посту.
 */
function updateRatingButtons() {
    $.ajax({
        url: '/post-ajax/update-post-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            let likeImg = document.getElementById('likePost');
            let dislikeImg = document.getElementById('dislikePost');

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
const deletePostButton = document.getElementById('deletePostButton');
if (deletePostButton !== null) {
    deletePostButton.addEventListener('click', () => {
        $.ajax({
            url: '/post-ajax/delete',
            cache: false,
            type: 'post',
            data: data,
            success: function () {
                location.href = ('/');
            }
        });
    });
}
