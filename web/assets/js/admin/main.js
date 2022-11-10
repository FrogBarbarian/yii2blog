let offset = params.get("offset") > 0 ? params.get("offset") : '-1';
let page = params.get("page") ?? '1';
let sortParam = params.get("sortParam") ?? 'id';
let sortOrder = params.get("sortOrder") ?? '4';
let table = '';
let model = '';

$(document).ready(function () {
    drawArrows();
});

/**
 *  Задает параметры для сортировки объектов.
 */
function sort(param) {
    if (offset !== '-1') {
        params.set('offset', offset);
    }

    if (page !== '1') {
        params.set('page', page);
    }

    params.set('sortParam', param);
    sortOrder = sortOrder === '4' ? '3' : '4';
    params.set('sortOrder', sortOrder);
    location.href = url.toString();
}

/**
 * Получает объекты для вывода.
 */
function getObjects() {
    let data = {
        model: model,
        offset: offset,
        page: page,
        sortParam: sortParam,
        sortOrder: sortOrder,
    };
    $.ajax({
        url: '/admin-ajax/get-objects',
        cache: false,
        data: data,
        success: function (response) {
            construct(response)
        }
    });
}

/**
 * Рисует стрелки сортировки.
 */
function drawArrows() {
    let activeArrow = $('#arrow_' + sortParam);
    activeArrow.css('color', 'white');
    activeArrow.html(sortOrder === '4' ? '&darr;' : '&uarr;');
}

/**
 * Заполняет поле ввода количества отображаемых элементов.
 */
function setOffsetInput(offset) {
    $('#setOffset').val(offset);
}
