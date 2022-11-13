/**
 * Отрисовывает небольшое уведомление в углу экрана.
 */
function notice(header, text) {
    const content = $("#content");
    content.append(
        '<div id="notice" class="notice-window">' +
        '<h6>' +
        header +
        '</h6>' +
        '<p class="small">' +
        text +
        '</p>' +
        '</div>'
    );
    setTimeout(() => {
        content.children('#notice').remove();
    }, 2000);
}
