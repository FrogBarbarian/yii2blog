<?php

namespace app\controllers;

use app\models\Bills;
use app\models\Investments;

class ProfileController extends AppController
{
    public function actionIndex()
    {
        $billsForm = new Bills();
        $model = new Investments();
        $bills = $model->getBills();
        if ($billsForm->load(\Yii::$app->request->post()) && $billsForm->validate()) {
            if (isset(\Yii::$app->request->post('Bills')['isNew'])) {
                $model->addBill($billsForm->attributes);
                $this->redirect('/profile');
            }
            if (isset(\Yii::$app->request->post('Bills')['deleteBill'])) {
                $model->deleteBill(\Yii::$app->request->post('Bills')['id']);
                $this->redirect('/profile');
            }
            if (isset(\Yii::$app->request->post('Bills')['isEdit'])) {
                $model->editBill(\Yii::$app->request->post('Bills')['id'], $billsForm->attributes);
                $this->redirect('/profile');
            }
        }
        $this->view->params = ['menubar' => 1];

        return $this->render('profile', ['bills' => $bills, 'model' => $billsForm]);
    }

    public function actionBills()
    {
        $billsForm = new Bills();
        $investmentsModel = new Investments();
        $bills = $investmentsModel->getBills();
        $this->view->params = ['menubar' => 1];
        return $this->render('bills', ['bills' => $bills, 'model' => $billsForm]);
    }
}