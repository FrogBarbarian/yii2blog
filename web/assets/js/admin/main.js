let sortParam = 'id';
let sortAsc = true;
let tab = new URLSearchParams(window.location.search).get('tab')

/**
 * Получает объекты из БД.
 */
function getObjects(callback, object = 'tags', param = 'id', type = 4, useCache = true) {
    $.ajax({
        url: '/admin-u-i/get-object',
        cache: false,
        data: {object: object, param: param, type: type, useCache: useCache},
        success: function (response) {
            callback(response);
        }
    });
}

/**
 *  Сортирует объекты.
 */
function sort(param) {
    let isNewParam = param !== sortParam;

    if (isNewParam) {
        $('#arrow_' + sortParam).css('color', '');
        $('#arrow_' + param).css('color', 'white');
        sortParam = param;
        sortAsc = true;
        let filters = document.querySelectorAll('[id^="arrow_"]');

        filters.forEach((element) => {
            element.innerHTML = '&darr;';
        })
    } else {
        sortAsc = !sortAsc;
        if (sortAsc) {
            $('#arrow_' + param).html('&darr;');
        } else {
            $('#arrow_' + param).html('&uarr;');
        }
    }

    let type = sortAsc ? 4 : 3;
    getObjects(construct, tab, sortParam, type, false);
}
