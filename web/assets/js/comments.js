/**
 * Поле содержащее значение поля 'комментарий'.
 */
const commentValueHiddenInput = document.getElementById('commentValue');
/**
 * Div контейнер для ввода комментария.
 */
const commentInput = document.getElementById('commentInput');
/**
 * Кнопка скрытия комментариев.
 */
const hideCommentsButton = document.getElementById('hideComments');
/**
 * Контейнер с комментариями.
 */
const comments = $('#comments');

setInterval(updateComments, 2000);

/**
 * Добавляет комментарий.
 */
document.getElementById('addComment').addEventListener('click', () => {
    let data = {
        _csrf: token,
        ajax: {
            postId: postId,
            comment: commentValueHiddenInput.value,
        },
    }
    $.ajax({
        url: '/comment/add-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response === false) {
                commentInput.innerText = '';
                commentValueHiddenInput.value = '';
                $('#commentErrorLabel').html('');
                updateComments(data);
                updateCommentsAmount(data);
            } else {
                $('#commentErrorLabel').html(response['comment'][0]);
            }
        }
    });
});

/**
 * Заполняет значение скрытого поля 'commentValue' вводом в div 'commentInput'.
 */
commentInput.addEventListener('input', (e) => {
    commentValueHiddenInput.value = e.target.innerText;
});

/**
 * Скрывает/отображает комментарии.
 */
if (hideCommentsButton !== null) {
    hideCommentsButton.addEventListener('click', () => {
        if (comments.css('display') === 'none') {
            comments.show();
            hideCommentsButton.innerText = 'Скрыть комментарии';
        } else {
            comments.hide();
            hideCommentsButton.innerText = 'Показать комментарии';
        }

    });
}

/**
 * Лайкает комментарий.
 */
function likeComment(id) {
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/comment/like-comment',
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
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/comment/dislike-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            updateCommentRatingButtons(data);
            $('#commentRating' + id).html(html);
        },
    });
}

/**
 * Дорисовывает комментарии, если в посте выведены не все.
 */
function updateComments() {
    let curCommentsAmount = comments.children('li').length;
    let data = {
        _csrf: token,
        ajax: {
            postId: postId,
            curCommentsAmount: curCommentsAmount,
        },
    }
    $.ajax({
        url: '/comment/append-comments',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response !== false) {
                const html = comments.html();
                comments.html(html + response);
            }

            updateCommentRating();
        }
    });
}

/**
 * Обновляет цвет кнопок "лайк" и "дизлайк" к комментарию.
 */
function updateCommentRatingButtons(data,) {
    let id = data['ajax']['commentId'];
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
function updateCommentRating() {
    let data = {
        _csrf: token,
        ajax: {},
    }
    let commentBlock = document.getElementById('comments');
    let commentsRatingElements = commentBlock.querySelectorAll('div.comment-rating');
    let comments = [];

    for (let i = 0; i < commentsRatingElements.length; i++) {
        comments.push({
            id: parseInt(commentsRatingElements[i].id.match(/\d+/).toString()),
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
