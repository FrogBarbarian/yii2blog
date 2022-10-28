<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 */

?>
<link rel="stylesheet" type="text/css" href="../../assets/css/pages-indexation.css"/>
<div style="display: flex; border: black 1px solid;padding: .5rem">
    <div style="border: grey 1px solid;padding: 3px;margin: 3px;display: flex; flex-direction: column">
        <button name="inboxMails" type="button" class="toolbar-button" title="Входящие">
            <img src="<?= IMAGES ?>/button-mail.svg" alt="mail">
        </button>
        <button name="sentMails" type="button" class="toolbar-button" title="Отправленные">
            <img src="<?= IMAGES ?>/button-sent.svg" alt="sent">
        </button>
        <button name="newMessage" type="button" class="toolbar-button" title="Новое сообщение" style="margin-top: 1rem">
            <img src="<?= IMAGES ?>/button-new-message.svg" alt="new">
        </button>
        <button name="refreshMessages" type="button" class="toolbar-button" title="Обновить">
            <img src="<?= IMAGES ?>/button-update.svg" alt="refresh">
        </button>
    </div>
    <div id="mails" style="border: grey 1px solid;padding: 3px;margin: 3px;width: 100%">

    </div>
</div>

<script>
    $(document).ready(() => {
        renderMails();
    })

    let tab = 'inbox';
    const mails = document.querySelector('[id="mails"]');

    document.querySelector('[name="inboxMails"]').addEventListener('click', () => {
        tab = 'inbox';
        renderMails();
    });
    document.querySelector('[name="sentMails"]').addEventListener('click', () => {
        tab = 'sent';
        renderMails();
    });
    document.querySelector('[name="newMessage"]').addEventListener('click', () => {
        createMessageModal();
    });
    document.querySelector('[name="refreshMessages"]').addEventListener('click', () => {
        renderMails();

    });


    function renderMails(page = 1) {
        let data = {
            _csrf: token,
            ajax: {
                event: tab,
                page: page,
            },
        };
        $.ajax({
            url: '/profile/get-mails',
            cache: false,
            type: 'post',
            data: data,
            success: function (response) {
                mails.innerHTML = response;
                setPageButtons();
            },
            error: function () {
                console.log('Error');
            }
        });
    }

    function createMessageModal() {
        $.ajax({
            url: '/u-i/message-modal',
            cache: false,
            type: 'post',
            data: {_csrf: token},
            success: function (response) {
                $('#modalDiv').html(response);
            }
        });
    }

    function setPageButtons() {
        let pageButtons = document.querySelectorAll('[class=btn-page]');
        if (pageButtons.length > 0) {
            for (let pageButton of pageButtons) {
                pageButton.addEventListener('click', () => {
                    renderMails(pageButton.value)
                })
            }
        }
    }

</script>