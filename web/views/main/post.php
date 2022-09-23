<?php

$this->title = $post['title'];
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <h5 class="card-title"><?=$post['title']?></h5>
                <p class="card-text"><?=$post['body']?></p>
            </div>
            <div class="card-footer">Написан: <b>дата</b>. Просмотров: <?=$post['viewed']?></div>
        </div>
        </div>
</div>
</div>
