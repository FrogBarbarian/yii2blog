$(document).ready(() => {
    renderMails();
})

/**
 * @type Открытая вкладка с письмами.
 */
let tab = 'inbox';

/**
 * @type Контейнер с письмами.
 */
let mails = document.getElementById('mails');

/**
 * Входящие сообщения.
 */
document.querySelector('[name="inboxMails"]').onclick = () => {
    tab = 'inbox';
    renderMails();
}

/**
 * Отправленные сообщения.
 */
document.querySelector('[name="sentMails"]').onclick = () => {
    tab = 'sent';
    renderMails();
}

/**
 * Папка спам.
 */
document.querySelector('[name="spamMails"]').onclick = () => {
    tab = 'spam';
    renderMails();
}

/**
 * Создает модальное окно для написания сообщения.
 */
document.querySelector('[name="newMessage"]').onclick = () => {
    createMessageModal();
}

/**
 * Обновляет список сообщений.
 */
document.querySelector('[name="refreshMessages"]').onclick = () => {
    renderMails();
}

/**
 * Отрисовывает список писем.
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
