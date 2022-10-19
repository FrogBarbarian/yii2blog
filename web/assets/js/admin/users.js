$(document).ready(function () {
    getObjects(construct)
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
            '</div>'
        );
    });
}
