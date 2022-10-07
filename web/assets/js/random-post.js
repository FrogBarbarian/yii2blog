$(function random() {
    $('#randomPost').click(function () {
        $.ajax({
            url: '/post-interface/random-post',
            success: function (data) {
                location.href=(data)
            },
        })
    })
})