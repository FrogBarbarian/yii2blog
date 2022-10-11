$(document).ready(function () {
    const postId = (new URL(document.location)).searchParams.get('id');
    const token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: postId,
        },
    };

    $('#addComment').click(function () {
        let form = $('#comment-form');
        let formData = form.serialize();
        $.ajax({
            url: '/posts/add-comment',
            cache: false,
            type: 'post',
            data: formData,
            success: function (response) {
                if (typeof (response) === "string") {
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
            $('#commentRating' + id).html(response);
        },
    });
};

function updateCommentRating(id) {

}

function dislikeComment(id) {
    var token = $('meta[name=csrf-token]').attr('content');
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/post-u-i/dislike-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            $('#commentRating' + id).html(html);
        },
    });
};

function updateCommentRating(commentRating) {
    let curRating = commentRating.textContent;
    data['ajax']['curCommentsAmount'] = curCommentsAmount;
}

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
        }
    });
}