$(document).ready(function () {
    getObjects(construct, 'statistics');
});

table = 'statistics';

/**
 * Отрисовывает пользователей.
 */
function construct(statistics) {
    const usersDiv = $('#objects');
    usersDiv.html('');

    statistics.forEach((statistic) => {
        usersDiv.html(usersDiv.html() +
            '<div class="user-card">' +
            '<h6>' +
            statistic['owner'] +
            '</h6>' +
            '<span style="font-size: x-small; display: block">' +
            'Постов: ' + statistic['posts'] +
            '</span>' +
            '<span style="font-size: x-small; display: block">' +
            'Комментариев: ' + statistic['comments'] +
            '</span>' +
            '<span style="font-size: x-small; display: block">' +
            'Просмотров: ' + statistic['views'] +
            '</span>' +
            '<span style="font-size: x-small; display: block">' +
            'Рейтинг: ' + statistic['rating'] +
            '</span>' +
            '<span style="font-size: x-small; display: block">' +
            'Лайков/дизлайков: ' + statistic['likes'] + '/' + statistic['dislikes'] +
            '</span>' +
            '<a href="/user?id=' +
            statistic['id'] +
            '" class="author-link" style="font-size: small">' +
            'Профиль' +
            '</a>' +
            '</div>'
        );
    });
}
