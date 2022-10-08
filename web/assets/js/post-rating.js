$(document).ready(function () {
    var token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: $('#postId').val()
        }
    };
    $('#like').click(function () {
        $.ajax({
            url: '/post-interface/like-post',
            cache: false,
            type: 'post',
            data: data,
            success: function (html) {
                $("#rating-container").html(html);
                updateRatingButtons(data);
            },
        });
    });
    $('#dislike').click(function () {
        $.ajax({
            url: '/post-interface/dislike-post',
            cache: false,
            type: 'post',
            data: data,
            success: function (html) {
                $("#rating-container").html(html);
                updateRatingButtons(data);
            }
        });
    });
    updateRating();
    setInterval('updateRating()', 2000);
});


function updateRating() {
    var token = $('meta[name=csrf-token]').attr("content");
    let data = {
        _csrf: token,
        ajax: {
            postId: $('#postId').val()
        }
    };
    $.ajax({
        url: "/post-interface/update-post-rating",
        cache: false,
        type: 'post',
        data: data,
        success: function (html) {
            $("#rating-container").html(html);
        }
    });
}

function updateRatingButtons(data) {
    $.ajax({
        url: '/post-interface/update-rating-buttons',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response[0] === true) {
                document.getElementById('like').style.backgroundColor = 'green';
            } else {
                document.getElementById('like').style.backgroundColor = 'grey';
            }

            if (response[1] === true) {
                document.getElementById('dislike').style.backgroundColor = 'red';
            } else {
                document.getElementById('dislike').style.backgroundColor = 'grey';
            }
        }
    });
}
