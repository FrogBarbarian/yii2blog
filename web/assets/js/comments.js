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

/**
 * Добавляет комментарий.
 */
document.getElementById('addComment').onclick = () => {
    let data = {
        _csrf: token,
        postId: postId,
        comment: commentValueHiddenInput.value,
    }
    $.ajax({
        url: '/comment-ajax/add-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response === true) {
                commentInput.innerText = '';
                commentValueHiddenInput.value = '';
                $('#commentErrorLabel').html('');
                updateComments();
            } else {
                $('#commentErrorLabel').html(response['comment'][0]);
            }
        }
    });
}

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
 * Ставит лайк/дизлайк комментарию.
 */
function likeOrDislikeComment(id, isLike) {
    let data = {
        _csrf: token,
        commentId: id,
        isLike: isLike,
    }
    $.ajax({
        url: '/comment-ajax/like-or-dislike',
        type: 'post',
        data: data,
        success: function (response) {
            updateCommentRatingButtons(data, id);
            $('#commentRating' + id).html(response);
        }
    });
}

/**
 * Обновляет цвет кнопок "лайк" и "дизлайк" к комментарию.
 */
function updateCommentRatingButtons(data) {
    let id = data['commentId'];
    $.ajax({
        url: '/comment-ajax/update-rating-buttons',
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
function updateCommentsRating() {
    let commentBlock = document.getElementById('comments');
    let commentsRatingElements = commentBlock.querySelectorAll('div.comment-rating');
    let comments = [];

    for (let i = 0; i < commentsRatingElements.length; i++) {
        comments.push({
            id: parseInt(commentsRatingElements[i].id.match(/\d+/).toString()),
            rating: commentsRatingElements[i].textContent,
        });
    }

    let data = {
        _csrf: token,
        comments: comments,
    }
    $.ajax({
        url: '/comment-ajax/update-rating',
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

/**
 * Удаляет/восстанавливает комментарий.
 * @param {String} id ID комментария.
 */
function deleteComment(id) {
    let data = {
        _csrf: token,
        id: id,
    }
    $.ajax({
        url: '/comment-ajax/delete',
        type: 'post',
        data: data,
        success: function () {
            location.reload();
        }
    });
}
