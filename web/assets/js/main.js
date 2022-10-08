$(function random() {
    $('#randomPost').click(function () {
        $.ajax({
            url: '/post-interface/random-post',
            success: function (data) {
                location.href = (data);
            },
        });
    });
})

$(document).ready(function () {
    $('#arrowTop').click (function() {
        window.scrollTo(0, 0);
    });

    window.addEventListener('scroll', function() {
        arrowTop.hidden = (pageYOffset < document.documentElement.clientHeight);
    })
})

