/**
 * Кнопка изменения прав комментировать пользователю.
 */
const changeCommentPermissionsButton = document.getElementById('changeCommentPermissionsButton');
/**
 * Поле <span> с информацией о разрешении комментировать пользователю.
 */
const commentPermissionsField = document.getElementById('commentPermissions');
/**
 * Кнопка изменения прав создавать посты пользователю.
 */
const changePostPermissionsButton = document.getElementById('changePostPermissionsButton');
/**
 * Поле <span> с информацией о разрешении создавать посты пользователю.
 */
const postPermissionsField = document.getElementById('postPermissions');
/**
 * Кнопка изменения прав создавать посты пользователю.
 */
const changeMessagesPermissionsButton = document.getElementById('changeMessagesPermissionsButton');
/**
 * Поле <span> с информацией о разрешении писать сообщения пользователю.
 */
const messagePermissionsField = document.getElementById('messagePermissions');
/**
 * Кнопка отрисовки модального окна с назначением пользователя администратором.
 */
const createAdminModalButton = document.getElementById('createAdminModalButton');
/**
 * Кнопка для бана/разбана пользователя.
 */
const banUserButton = document.getElementById('banUserButton');
/**
 * Имя пользователя профиля.
 */
const username = decodeURI(url.pathname.slice(7));

/**
 * Меняет права пользователя на написание комментариев.
 */
changeCommentPermissionsButton.onclick = () => {
    let data = {
        _csrf: token,
        username: username,
    }

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
changePostPermissionsButton.addEventListener('click', () => {
    let data = {
        _csrf: token,
        username: username,
    }

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
});

/**
 * Меняет права пользователя на написание личных сообщений.
 */
changeMessagesPermissionsButton.addEventListener('click', () => {
    let data = {
        _csrf: token,
        username: username,
    }

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
});

createAdminModalButton.addEventListener('click', () => {
    let data = {
        _csrf: token,
        username: username,
    };
    $.ajax({
        url: '/profile-ajax/create-set-user-as-admin-window',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
});

/**
 * Делает пользователя админом.
 */
function setUserAdmin() {
    let data = {
        _csrf: token,
        ajax: {
            username: username,
        },
    };
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
banUserButton.addEventListener('click', () => {
    let data = {
        _csrf: token,
        username: username,
    };
    $.ajax({
        url: '/profile-ajax/set-user-ban',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            location.href = window.location;
        },
    });
});
