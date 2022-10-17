$(document).ready(function () {
    getObjects(construct, 'users');
});

/**
 * Отрисовывает пользователей.
 */
function construct(users) {
    const usersDiv = $('#objects');
    usersDiv.html('');

    users.forEach((user) => {
        usersDiv.html(usersDiv.html() +

            '<div class="user-card">' +
            '<h6>' +
            user['username'] +
            '</h6>' +
            '<span style="font-size: x-small; display: block">' +
            (user['is_admin'] ? 'Администратор' : 'Пользователь') +
            '</span>' +
            '<a href="/user?id=' +
            user['id'] +
            '" class="author-link" style="font-size: small">' +
            'Профиль' +
            '</a>' +
            '</div>'
        );
    });
}
