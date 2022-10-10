$(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: $('#postId').val(),
            isVisible: $('#commentsAllowed').val(),
        },
    };
    $('#commentsButton').click(function () {
        $.ajax({
            url: '/u-i/comment-rule',
            cache: false,
            type: 'post',
            data: data,
            success: function (html) {
                $("#commentsButton").html(html);
            },
        });
        $.ajax({
            url: '/u-i/comments-permissions',
            cache: false,
            type: 'post',
            data: data,
            success: function (html) {
                $("#comments-permissions").html(html);
            },
        });
    });
    updateComments();
    setInterval('updateComments()', 2000);
});


function updateComments() {
    var token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: $('#postId').val(),
        },
    };
    $.ajax({
        url: "/u-i/update-comments",
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            $("#comments").html(html[0]);
            $("#commentsAmount").html(html[1]);
        },
    });
};

function likeComment(id) {
    var token = $('meta[name=csrf-token]').attr('content');
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/u-i/like-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            $('#commentRating' + id).html(html);
            updateComments();
        },
    });
};

function dislikeComment(id) {
    var token = $('meta[name=csrf-token]').attr('content');
    let data = {
        _csrf: token,
        ajax: {
            commentId: id,
        },
    };
    $.ajax({
        url: '/u-i/dislike-comment',
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            $('#commentRating' + id).html(html);
            updateComments();
        },
    });
};