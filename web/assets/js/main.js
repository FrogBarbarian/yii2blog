/**
 * Текущий URL.
 */
let url = (new URL(document.location));

/**
 * GET параметры.
 */
let params = url.searchParams;

/**
 * _csrf токен.
 */
const token = $('meta[name=csrf-token]').attr("content");

window.onload = () => {
    /**
     * Кнопка прокрутки страницы наверх.
     */
    let arrowTop = document.getElementById('arrowTop');

    /**
     * Скроллит страницу на самый верх.
     */
    arrowTop.onclick = () => {
        window.scrollTo(0, 0);
    }

    /**
     * Прячет и показывает стрелку для прокрутки страницы вверх.
     */
    window.onscroll = () => {
        arrowTop.hidden = (pageYOffset < (document.documentElement.clientHeight / 2));
    }
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
    window.stop(false, true);
    window.animate({left: '-=1rem'}, 50);
    window.animate({left: '+=2rem'}, 100);
    window.animate({left: '-=1rem'}, 50);
}
