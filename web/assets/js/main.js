let url = (new URL(document.location));
let params = url.searchParams;
const token = $('meta[name=csrf-token]').attr("content");

window.onload = () => {
    const arrowTop = document.getElementById('arrowTop');

    /**
     * Скроллит страницу на самый верх.
     */
    arrowTop.addEventListener('click', () => {
        window.scrollTo(0, 0);
    });

    /**
     * Прячет и показывает стрелку для прокрутки страницы вверх.
     */
    window.addEventListener('scroll', function () {
        arrowTop.hidden = (pageYOffset < (document.documentElement.clientHeight / 2));
    })
}



/**
 * Открывает страницу с рандомным постом.
 */
function randomPost() {
    $.ajax({
        url: '/u-i/random-post',
        success: function (response) {
            location.href = (response);
        },
    });
}

/**
 * Закрывает модальное окно.
 */
function closeModalDiv() {
    $('#modalDiv').html('');
}

/**
 * Ловит нажатие кнопки Esc при открытом модальном окне.
 */
window.onkeyup = function (e) {
    const elementExists = document.getElementById("modalWindow");
    if (elementExists !== null && e.keyCode === 27) closeModalDiv();
}

/**
 * Отыгрывает анимацию потряхивания модального окна.
 */
function shakeModal() {
    const window = $('.modal-window');
    window.animate({left: '-=1rem'}, 50);
    window.animate({left: '+=2rem'}, 100);
    window.animate({left: '-=1rem'}, 50);
}

