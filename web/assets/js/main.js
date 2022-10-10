/**
 * Открывает страницу с рандомным постом.
 */
$(function random() {
    $('#randomPost').click(function () {
        $.ajax({
            url: '/u-i/random-post',
            success: function (data) {
                location.href = (data);
            },
        });
    });
})

/**
 * Скроллит страницу на самый верх.
 */
function goTop() {
    window.scrollTo(0, 0);
}

/**
 * Прячет и показывает стрелку для прокрутки страницы вверх.
 */
window.addEventListener('scroll', function () {
    arrowTop.hidden = (pageYOffset < (document.documentElement.clientHeight / 2));
})
