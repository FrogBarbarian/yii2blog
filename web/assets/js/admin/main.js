let sortParam = 'id';
let sortAsc = true;
let table = '';
let model = '';


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
        $('#arrow_' + param).html(sortAsc ? '&darr;' : '&uarr;');
    }
    getObjects(construct, model);
}

function getObjects(callback) {
    let offset = getParams.get("offset") > 0 ? getParams.get("offset") : '-1';
    let page = getParams.get("page") ?? '1';
    let sortOrder = sortAsc ? '4' : '3';
    let data = {
        model: model,
        offset: offset,
        page: page,
        sortParam: sortParam,
        sortOrder: sortOrder,
    };
    $.ajax({
        url: '/admin-u-i/get-objects',
        cache: false,
        data: data,
        success: function (response) {
            callback(response)
        }
    });
}