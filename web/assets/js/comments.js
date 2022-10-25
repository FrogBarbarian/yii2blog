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
            url: form.attr('action'),
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
                    $('#commentErrorLabel').html(response['comment'][0]);
                }
            }
        });
    });

    $('#hideComments').click(function () {
        if ($('#comments').css('display') === 'none') {
            $('#comments').show();
            $('#hideComments').html('Скрыть комментарии')
        } else {
            $('#comments').hide();
            $('#hideComments').html('Показать комментарии')
        }

    });

    setInterval(updateComments, 2000, data);
});

/**
 * Лайкает комментарий.
 */
function likeComment(id) {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr('content'),
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/comments/like-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            updateCommentRatingButtons(data, id);
            $('#commentRating' + id).html(response);
        },
    });
}

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
        url: '/comments/dislike-comment',
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
    data['ajax']['curCommentsAmount'] = comments.getElementsByTagName('li').length;
    $.ajax({
        url: '/comment/append-comments',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                const html = comments.innerHTML;
                $('#comments').html(html + response);
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
        url: '/comment/update-comment-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            let likeImg = document.getElementById('commentLike' + id);
            let dislikeImg = document.getElementById('commentDislike' + id);

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
 *  Обновляет рейтинг всех комментариев к посту.
 */
function updateCommentRating(token) {
    let data = {
        _csrf: token,
        ajax: {},
    }
    let commentBlock = document.getElementById('comments');
    let commentsRatingElements = commentBlock.querySelectorAll('div.comment-rating');
    let comments = [];

    for (let i = 0; i < commentsRatingElements.length; i++) {
        comments.push({
            id: parseInt(commentsRatingElements[i].id.match(/\d+/)),
            rating: commentsRatingElements[i].textContent,
        });
    }

    data['ajax'].comments = comments;
    $.ajax({
        url: '/comment/comments-update-rating',
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
