<?php

use yii\widgets\ActiveForm;

$this->title = 'Мои счета';
?>

<div class="container bg-black rounded-4 border-4 border-warning opacity-75 vh-100">
<?php if ($bills): ?>
    <div class="row row-cols-1 row-cols-md-3 py-3 mx-2 justify-content-around">
        <?php foreach ($bills as $bill): ?>
        <div class="col card bg-dark rounded-4 m-1 border-4 border-warning form-control" style="max-width: 300px">
                <div class="card-body">
                    <h5 class="card-title text-warning"><?=$bill['title']?></h5>
                    <h6 class="small text-danger">На счету: <?=$bill['amount']?> рублей</h6>
                    <?php
                    switch ($bill['term']) {
                        case 0: echo '<p class="small text-warning">Бессрочный</p>'; break;
                        default: echo '<p class="small text-warning">Срок вклада: ' . $bill['term'] . ' лет</p>';break;
                    }
                    ?>
                    <div class="hstack gap-1">
                        <?php $form = ActiveForm::begin(['id' => 'bill-delete-id' . $bill['id']]); ?>
                        <?=$form->field($model, 'id')->hiddenInput([
                            'value' => $bill['id'],
                        ])
                        ?>
                        <?=$form->field($model, 'title')->hiddenInput([
                            'value' => $bill['title'],
                        ])->error(false)
                        ?>
                        <?=$form->field($model, 'amount')->hiddenInput([
                            'value' => $bill['amount'],
                        ])->error(false)
                        ?>
                        <?=$form->field($model, 'deleteBill')->hiddenInput([
                            'value' => true,
                        ])
                        ?>
                        <input type="submit" class="btn btn-sm btn-outline-warning justify-content-start me-2" value="Удалить">
                        <?php ActiveForm::end(); ?>
                        <button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="modal" data-bs-target="#editBill<?=$bill['id']?>">
                            Изменить данные
                        </button>

                    </div>

                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="text-center p-5">
        <h1 class="text-warning">Счета не найдены...</h1>
        <h3 class="text-warning fw-light">Но вы можете создать новый.</h3>
    </div>
<?php endif ?>
</div>
