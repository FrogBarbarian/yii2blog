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
        url: '/site/search-suggestion',
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
