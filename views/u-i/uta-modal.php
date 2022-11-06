<?php

declare(strict_types=1);

/**
 * @var string $username
 */

?>
<div id="modalWindow" class="modal-window-back" tabindex="-1">
    <div class="modal-window" style="width: 350px; max-width: 90vw">
        <div class="modal-window-header">
            <b>Вы уверены?</b>
            <button type="button" class="btn-close" onclick="closeModalDiv()">
            </button>
        </div>
        Это назначит пользователя <b><?= $username ?></b> администратором.
        Отменить возможно через прямой доступ к БД.
        <div class="modal-window-footer">
            <button type="button" class="btn-basic" onclick="closeModalDiv()">
                Отмена
            </button>
            <button onclick="setUserAdmin()" type="button" class="btn-basic">
                Подтвердить
            </button>
        </div>
    </div>
</div>
