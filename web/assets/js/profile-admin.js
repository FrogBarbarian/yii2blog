/**
 * Меняет права пользователя на написание комментариев.
 */
function setCommentsPermissions(button) {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        id: $('#userId').val(),
    };
    $.ajax({
        url: '/profile-interface/set-comments-permissions',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response) {
                $(button).html('Комментарии разрешены');
            } else {
                $(button).html('Комментарии запрещены');
            };
        },
    });
};

/**
 * Меняет права на создание постов.
 */
function setCreatePostsPermissions(button) {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        id: $('#userId').val(),
    };
    $.ajax({
        url: '/profile-interface/set-create-posts-permissions',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response) {
                $(button).html('Писать посты разрешено');
            } else {
                $(button).html('Писать посты запрещено');
            };
        },
    });
};

/**
 * Меняет права на использование ЛС.
 */
function setPrivateMessagesPermissions(button) {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        id: $('#userId').val(),
    };
    $.ajax({
        url: '/profile-interface/set-private-messages-permissions',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            if (response) {
                $(button).html('ЛС разрешены');
            } else {
                $(button).html('ЛС запрещены');
            };
        },
    });
};

/**
 * Делает пользователя админом.
 */
function setUserAdmin() {
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        id: $('#userId').val(),
    };
    $.ajax({
        url: '/profile-interface/set-user-admin',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            location.href = window.location;
        },
    });
};
