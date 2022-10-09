/**
 * Открывает страницу с рандомным постом.
 */
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

/**
 * При коике на кнопку страницу скроллится в самый верх.
 */
$('#arrowTop').click(function () {
    window.scrollTo(0, 0);
});

/**
 * Прячет и показывает стрелку для прокрутки страницы вверх.
 */
window.addEventListener('scroll', function () {
    arrowTop.hidden = (pageYOffset < (document.documentElement.clientHeight / 2));
})
