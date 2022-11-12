$(document).ready(() => {
    renderMails();
})

/**
 * @type {string} Открытая вкладка с письмами.
 */
let tab = 'inbox';
/**
 * @type {Element} Контейнер с письмами.
 */
const mails = document.querySelector('[id="mails"]');

document.querySelector('[name="inboxMails"]').addEventListener('click', () => {
    tab = 'inbox';
    renderMails();
});
document.querySelector('[name="sentMails"]').addEventListener('click', () => {
    tab = 'sent';
    renderMails();
});
document.querySelector('[name="spamMails"]').addEventListener('click', () => {
    tab = 'spam';
    renderMails();
});
document.querySelector('[name="newMessage"]').addEventListener('click', () => {
    createMessageModal();
});
document.querySelector('[name="refreshMessages"]').addEventListener('click', () => {
    renderMails();

});

/**
 * Отрисовывает список писем.
 * @param page Текущая страница.
 */
function renderMails(page = 1) {
    let data = {
        _csrf: token,
        event: tab,
        page: page,
    };
    $.ajax({
        url: '/profile-ajax/get-mails',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            mails.innerHTML = response;
            setPageButtons();
        }
    });
}

/**
 * Добавляет обработчик событий к кнопкам переключения страниц с сообщениями.
 */
function setPageButtons() {
    let pageButtons = document.querySelectorAll('[class^=mailbox-page-switcher]');
    if (pageButtons.length > 0) {
        for (let pageButton of pageButtons) {
            pageButton.addEventListener('click', () => {
                renderMails(pageButton.value)
            })
        }
    }
}
