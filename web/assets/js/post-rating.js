$(document).ready(function () {
    $('#like').click(function () {
        let data = {
            ajax: {
                userId: $('#user-id').val(),
                postId: $('#post-id').val()
            }
        };
        $.ajax({
            url: '/interface/like-post',
            cache: false,
            data: data,
            success: function (res) {
                $("#rating-container").html(res);
            }
        });
    });
});

$(document).ready(function () {
    $('#dislike').click(function () {
        let data = {
            ajax: {
                userId: $('#user-id').val(),
                postId: $('#post-id').val()
            }
        };
        $.ajax({
            url: '/interface/dislike-post',
            cache: false,
            data: data,
            success: function (res) {
                $("#rating-container").html(res);
                console.log(res)
            }
        });
    });
});

function show() {
    let data = {
        ajax: {
            postId: $('#post-id').val()
        }
    };
    $.ajax({

        url: "/interface/update-post-rating",
        cache: false,
        data: data,
        success: function (html) {
            $("#rating-container").html(html);
        }
    });
}

$(document).ready(function () {
    show();
    setInterval('show()', 1000);
});