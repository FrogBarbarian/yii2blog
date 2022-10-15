const token = $('meta[name=csrf-token]').attr("content");

function changeVisibility(checkbox) {
    let data = {
        _csrf: token,
        ajax: {
            isVisible: checkbox.checked,
        },
    };
    $.ajax({
        url: '/settings/change-visibility',
        cache: false,
        type: 'post',
        data: data,
        success: function () {
            alert('Настройки изменены');
        }
    });
}
