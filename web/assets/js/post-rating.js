$(document).ready(function () {
    let data = {
        ajax: {
            userId: $('#userId').val(),
            postId: $('#postId').val()
        }
    };
    $('#like').click(function () {
        $.ajax({
            url: '/post-interface/like-post',
            cache: false,
            data: data,
            success: function (html) {
                $("#rating-container").html(html);
            }
        });
    });
    $('#dislike').click(function () {
        $.ajax({
            url: '/post-interface/dislike-post',
            cache: false,
            data: data,
            success: function (html) {
                $("#rating-container").html(html);
            }
        });
    });
    update();
    setInterval('update()', 2000);
});


function update() {
    let data = {
        ajax: {
            postId: $('#postId').val()
        }
    };
    $.ajax({

        url: "/post-interface/update-post-rating",
        cache: false,
        data: data,
        success: function (html) {
            $("#rating-container").html(html);
        }
    });
}
