let url = (new URL(document.location));
let getParams = url.searchParams;
const token = $('meta[name=csrf-token]').attr("content");

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

/**
 * Создает модальное окно жалобы.
 * @param objectType Тип объекта.
 * @param objectId ID объекта.
 */
function createComplaint(objectType, objectId) {
    let data = {
        _csrf: token,
        ajax: {
            objectType: objectType,
            objectId: objectId,
        },
    };
    $.ajax({
        url: '/u-i/create-complaint-window',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            $('#modalDiv').html(response);
        }
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
 * Выводит предложения по поиску.
 */
function suggestSearch(field) {
    const searchSuggest = $('#suggestedSearchField');

    if (field.value === '') {
        searchSuggest.html('')

        return false;
    }

    $.ajax({
        url: '/u-i/search-suggest',
        cache: false,
        data: {input: field.value},
        success: function (response) {
            if (response === false) {
                searchSuggest.html(
                    '<li class="list-group-item" style="font-size: small">' +
                    'Нет совпадений' +
                    '</li>');

                return false;
            }

            searchSuggest.html('');

            response.forEach((post) => {
                searchSuggest.html(searchSuggest.html() +
                    '<li tabindex="-1" onclick="goToPost(' +
                    post['id'] +
                    ')" class="list-group-item" style="font-size: small;cursor: pointer;">' +
                    post['title'].slice(0, 13) +
                    '...' +
                    '</li>'
                );
            });
        }
    });
}

/**
 * Скрывает предложения поиска.
 */
function removeSuggest() {
    setTimeout(function () {
        $('#suggestedSearchField').html('');
    }, 150);
}

/**
 * Открывает пост по ID.
 */
function goToPost(id) {
    location.href = '/post?id=' + id;
}
