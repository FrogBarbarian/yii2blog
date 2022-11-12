<?php

declare(strict_types=1);

namespace app\components;

use app\models\Complaint;
use yii\web\NotFoundHttpException;

class AdminPanelComplaintWidget extends \yii\base\Widget
{
    /**
     * @var Complaint Жалоба.
     */
    public Complaint $complaint;
    /**
     * @var string Ссылка на объект жалобы.
     */
    private string $link;
    /**
     * @var string Название объекта в винительном падеже.
     */
    private string $targetObject;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $objectId = $this->complaint->getObjectId();
        $object = $this->complaint->getObject();

        if ($object === 'user') {
            $this->link = "/site/find-profile?id=$objectId";
            $this->targetObject = 'пользователя';
        } elseif ($object === 'comment') {
            $this->link = "/site/find-comment?id=$objectId";
            $this->targetObject = 'комментарий';
        } elseif ($object === 'post') {
            $this->link = "/post?id=$objectId";
            $this->targetObject = 'пост';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('admin-panel-complaint', [
            'complaint' => $this->complaint,
            'link' => $this->link,
            'targetObject' => $this->targetObject,
        ]);
    }
}
