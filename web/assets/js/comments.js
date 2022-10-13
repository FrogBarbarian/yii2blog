$(document).ready(function () {
    const postId = (new URL(document.location)).searchParams.get('id');
    const token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: postId,
        },
    };

    /**
     * Добавляет комментарий.
     */
    $('#addComment').click(function () {
        let form = $('#comment-form');
        let formData = form.serialize();
        $.ajax({
            url: '/posts/add-comment',
            cache: false,
            type: 'post',
            data: formData,
            success: function (response) {
                if (response === false) {
                    document.getElementById("commentArea").value = document.getElementById("commentArea").defaultValue;
                    $('#errorLabel').html('');
                    updateComments(data);
                    updateCommentsAmount(data);
                } else {
                    $('#errorLabel').html(response['comment'][0]);
                }
            }
        });
    });

    setInterval(updateComments, 2000, data);
});

/**
 * Лайкает комментарий.
 */
function likeComment(id) {
    var token = $('meta[name=csrf-token]').attr('content');
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/comments-u-i/like-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            updateCommentRatingButtons(data, id);
            $('#commentRating' + id).html(response);
        },
    });
};

/**
 * Дизлайкает комментарий.
 */
function dislikeComment(id) {
    var token = $('meta[name=csrf-token]').attr('content');
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/comments-u-i/dislike-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            updateCommentRatingButtons(data, id);
            $('#commentRating' + id).html(html);
        },
    });
};

/**
 * Дорисовывает комментарии, если в посте выведены не все.
 */
function updateComments(data) {
    let comments = document.getElementById('comments');
    let curCommentsAmount = comments.getElementsByTagName('li').length;
    data['ajax']['curCommentsAmount'] = curCommentsAmount;
    $.ajax({
        url: '/comments-u-i/append-comments',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                $html = comments.innerHTML;
                $('#comments').html($html + response);
            }

            updateCommentRating(data['_csrf']);
        }
    });
}

/**
 * Обновляет цвет кнопок "лайк" и "дизлайк" к комментарию.
 */
function updateCommentRatingButtons(data, id) {
    $.ajax({
        url: '/comments-u-i/update-comment-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            $likeButton = document.getElementById('commentLikeButton' + id);
            $dislikeButton = document.getElementById('commentDislikeButton' + id);

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

/**
 *  Обновляет рейтинг всех комментариев к посту.
 */
function updateCommentRating(token) {
    let data = {
        _csrf: token,
        ajax: {},
    }
    let commentBlock = document.getElementById('comments');
    let commentsRatingElements = commentBlock.querySelectorAll('div.comment-rating');
    var comments = [];

    for (i = 0; i < commentsRatingElements.length; i++) {
        comments.push({
            id: parseInt(commentsRatingElements[i].id.match(/\d+/)),
            rating: commentsRatingElements[i].textContent,
        });
    }

    data['ajax'].comments = comments;
    $.ajax({
        url: '/comments-u-i/comments-update-rating',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                for (let i = 0; i < commentsRatingElements.length; i++) {
                    $(commentsRatingElements[i]).html(response[i]['html'])
                }
            }
        }
    });
}
