<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\ComplaintForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Контроллер жалоб пользователей.
 */
class ComplaintController extends Controller
{
    /**
     * Создает окно для отправки жалобы.
     *
     * @throws NotFoundHttpException
     */
    public function actionCreateModalWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ComplaintForm();
        $objectType = $request->post('objectType');
        $objectId = $request->post('objectId');


        return $this->renderAjax('/ui/_complaint-modal', [
            'model' => $model,
            'objectType' => $objectType,
            'objectId' => $objectId,
        ]);
    }

    /**
     * Отправка жалобы.
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionSend(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ComplaintForm();
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            $sender = Yii::$app
                ->user
                ->getIdentity();
            (new Complaint())
                ->setObject($model->objectType)
                ->setObjectId($model->objectId)
                ->setSenderId($sender->getId())
                ->setSenderUsername($sender->getUsername())
                ->setComplaint($model->complaint)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(false);
    }
}
