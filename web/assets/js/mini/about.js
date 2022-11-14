/**
 * Отправка обратной связи.
 */
document.querySelector("button.btn-basic").onclick = () => {
    let feed = document.querySelector(".div-input-basic").textContent;
    $.ajax({
        url: '/site/about',
        type: 'post',
        data: {_csrf: token, feed: feed},
        success: function () {
            console.log()
        }
    });
}
