$(document).ready(function () {
    let data = {
        ajax: {
            postId: $('#postId').val(),
            isVisible: $('#commentsAllowed').val()
        }
    };
    $('#commentsButton').click(function () {
        $.ajax({
            url: '/post-interface/comment-rule',
            cache: false,
            data: data,
            success: function (html) {
                $("#commentsButton").html(html);
            }
        });
        $.ajax({
            url: '/post-interface/comments-permissions',
            cache: false,
            data: data,
            success: function (html) {
                $("#comments-permissions").html(html);
            }
        });
        $.ajax({
            url: '/post-interface/comment-form',
            cache: false,
            data: data,
            success: function (html) {
                // $("#comment-form").html(html);
            }
        });
    });
});

