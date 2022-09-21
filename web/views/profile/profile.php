<?php

use yii\widgets\ActiveForm;

$this->title = 'Главная страница';
?>
<?php /** @var \app\controllers\ProfileController $model */
if ($model->errors): ?>
    <div class="alert alert-danger" role="alert">
        <?php
        foreach ($model->errors as $error) {
            foreach ($error as $text) {
                echo $text . '<br>';
            }
        }
        ?>
    </div>
<?php endif ?>

<?php $form = ActiveForm::begin(['id' => 'bill-create']); ?>

<div>
    <button class="ms-2 mt-2 btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#addBill">
        Добавить счет
    </button>
    <div class="modal fade" id="addBill" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered justify-content-center" >
                <div class="modal-content bg-black rounded-4 border-4 border-warning opacity-75" style="max-width: 400px">
                    <div class="modal-body">
                        <h5 class="text-warning">Новый счет</h5>
                        <?=$form->field($model, 'title')->textInput([
                            'class' => 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning mt-2 placeholder-wave',
                            'placeholder' => 'Название',
                        ])->label(false)->error(false)
                        ?>
                        <p class="small card-text text-warning">Длина от 3 до 20 символов, можно использовать буквы, цифры, _ и -.</p>
                        <?=$form->field($model, 'amount')->textInput([
                            'class' => 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning mt-2 placeholder-wave',
                            'placeholder' => 'Сумма',
                        ])->label(false)->error(false)
                        ?>
                        <p class="small card-text text-warning">Не меньше 0, цифрами.</p>
                        <?=$form->field($model, 'term')->textInput([
                            'class' => 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning mt-2 placeholder-wave',
                            'placeholder' => 'Срок вклада',
                        ])->label(false)->error(false)
                        ?>
                        <p class="small card-text text-warning">Укажите срок вклада, если не бессрочный.</p>
                        <?=$form->field($model, 'isNew')->hiddenInput([
                            'value' => true,
                        ])
                        ?>
                        <input type="submit" class="mt-3 btn btn-outline-warning" value="Создать">
                    </div>
                </div>
            </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php if ($bills): ?>
    <div class="row row-cols-1 row-cols-md-3 ms-2">
        <?php foreach ($bills as $bill): ?>

            <div class="col card bg-black m-1 opacity-75" style="max-width: 300px">
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
            <div class="modal fade" id="editBill<?=$bill['id']?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered justify-content-center" >
                    <div class="modal-content bg-black rounded-4 border-4 border-warning opacity-75" style="max-width: 400px">
                        <div class="modal-body">
                            <h5 class="small card-text text-warning">Новое название</h5>
                            <?php $form = ActiveForm::begin(['id' => 'bill-edit-id' . $bill['id']]); ?>
                            <?=$form->field($model, 'id')->hiddenInput([
                                'value' => $bill['id'],
                            ])
                            ?>
                            <?=$form->field($model, 'title')->textInput([
                                'class' => 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning mt-2 placeholder-wave',
                                'placeholder' => 'название счета',
                                'value' => $bill['title'],
                            ])->label(false)->error(false)
                            ?>
                            <p class="small text-warning">Длина от 3 до 20 символов, можно использовать буквы, цифры, _ и -.</p>
                            <h5 class="small card-text text-warning">Сумма на счету</h5>
                            <?=$form->field($model, 'amount')->textInput([
                                'class' => 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning mt-2 placeholder-wave',
                                'placeholder' => 'Сумма',
                                'value' => $bill['amount']
                            ])->label(false)->error(false)
                            ?>
                            <h5 class="mt-2 small text-warning">Изменить срок</h5>
                            <?=$form->field($model, 'term')->textInput([
                                'class' => 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning mt-2 placeholder-wave',
                                'value' => $bill['term'] == 0 ? 'Бессрочный' : $bill['term'],
                                'placeholder' => 'Срок хранения',
                            ])->label(false)->error(false)
                            ?>
                            <?=$form->field($model, 'isEdit')->hiddenInput([
                                'value' => true,
                            ]);
                            ?>
                            <input type="submit" class="mt-3 btn btn-outline-warning" value="Внести изменения">
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php else: ?>
<div class="container">
    Счета не найдены
</div>
<?php endif ?>
