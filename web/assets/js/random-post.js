$(function random() {
    $('#randomPost').click(function () {
        $.ajax({
            url: '/interface/random-post',
            success: function (data) {
                location.href=(data)
            },
        })
    })
})