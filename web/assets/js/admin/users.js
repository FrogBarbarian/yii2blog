$(document).ready(function () {
    getObjects(construct);
});

table = 'users';
model = 'User';

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
            '<p style="font-size: x-small; margin: 1px">' +
            user['email'] +
            '</p>' +
            '<a class="author-link small" href="/user?id=' +
            user['id'] +
            '">' +
            (user['is_admin'] ? 'Администратор' : 'Пользователь') +
            '</a>' +
            '</div>'
        );
    });
}
