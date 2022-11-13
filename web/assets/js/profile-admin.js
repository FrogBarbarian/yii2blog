/**
 * Поле <span> с информацией о разрешении комментировать пользователю.
 */
let commentPermissionsField = document.getElementById('commentPermissions');

/**
 * Поле <span> с информацией о разрешении создавать посты пользователю.
 */
let postPermissionsField = document.getElementById('postPermissions');

/**
 * Поле <span> с информацией о разрешении писать сообщения пользователю.
 */
let messagePermissionsField = document.getElementById('messagePermissions');

/**
 * Имя пользователя профиля.
 */
let username = decodeURI(url.pathname.slice(7));
let data = {
    _csrf: token,
    username: username,
}

/**
 * Меняет права пользователя на написание комментариев.
 */
document.getElementById('changeCommentPermissionsButton').onclick = () => {
    $.ajax({
        url: '/profile-ajax/set-comments-permissions',
        type: 'post',
        cache: false,
        data: data,
        success: function (response) {
            if (response === true) {
                commentPermissionsField.innerText = '';

                return true;
            }

            commentPermissionsField.innerText = 'не';

            return false;
        }
    });
}

/**
 * Меняет права пользователя на написание постов.
 */
document.getElementById('changePostPermissionsButton').onclick = () => {
    $.ajax({
        url: '/profile-ajax/set-create-posts-permissions',
        type: 'post',
        cache: false,
        data: data,
        success: function (response) {
            if (response === true) {
                postPermissionsField.innerText = '';

                return true;
            }

            postPermissionsField.innerText = 'не';

            return false;
        }
    });
}

/**
 * Меняет права пользователя на написание личных сообщений.
 */
document.getElementById('changeMessagesPermissionsButton').onclick = () => {
    $.ajax({
        url: '/profile-ajax/set-private-messages-permissions',
        type: 'post',
        cache: false,
        data: data,
        success: function (response) {
            if (response === true) {
                messagePermissionsField.innerText = '';

                return true;
            }

            messagePermissionsField.innerText = 'не';

            return false;
        }
    });
}

/**
 * Рисует модальное окно для передачи пользователю прав администратора.
 */
document.getElementById('createAdminModalButton').onclick = () => {
    $('#modalDiv').html(
        '<div id="modalWindow" class="modal-window-back" tabindex="-1">' +
        '<div class="modal-window">' +
        '<div class="modal-window-header">' +
        '<b>Вы уверены?</b>' +
        '<button type="button" class="btn-close" onclick="closeModalDiv()">' +
        '</button>' +
        '</div>' +
        ' Это назначит пользователя' +
        ' <b>' +
        username +
        '</b>' +
        ' администратором.' +
        ' Отменить возможно через прямой доступ к БД.' +
        ' <div class="modal-window-footer">' +
        '<button type="button" class="btn-basic" onclick="closeModalDiv()">' +
        'Отмена' +
        '</button>' +
        '<button onclick="setUserAdmin()" type="button" class="btn-basic">' +
        'Подтвердить' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>'
    );
}

/**
 * Делает пользователя админом.
 */
function setUserAdmin() {
    $.ajax({
        url: '/profile-ajax/set-user-as-admin',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            location.href = window.location;
        },
    });
}

/**
 * Отправляет запрос на бан/разбан пользователя.
 */
document.getElementById('banUserButton').onclick = () => {
    $.ajax({
        url: '/profile-ajax/set-user-ban',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            location.href = window.location;
        },
    });
}
