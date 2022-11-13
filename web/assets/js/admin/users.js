$(document).ready(function () {
    getObjects(construct);
});

/**
 * @see table
 */
table = 'users';

/**
 * @see model
 */
model = 'User';

/**
 * Отрисовывает пользователей.
 */
function construct(users) {
    let usersDiv = $('#objects');
    usersDiv.html('');

    users.forEach((user) => {
        usersDiv.html(usersDiv.html() +
            '<div class="window-lite">' +
            '<h6>' +
            user['username'] +
            '</h6>' +
            '<p style="font-size: x-small; margin: 1px">' +
            user['email'] +
            '</p>' +
            '<a class="author-link small" href="/users/' +
            user['username'] +
            '">' +
            (user['is_admin'] ? 'Администратор' : 'Пользователь') +
            '</a>' +
            '</div>'
        );
    });
}
