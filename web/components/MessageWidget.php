<?php

declare(strict_types=1);

namespace app\components;

use yii\base\Widget;

/**
 * Сообщение.
 */
class MessageWidget extends Widget
{
    /**
     * @var string Субъект (от кого/кому).
     */
    public string $head = '';
    /**
     * @var string Тема.
     */
    public string $subject = '';
    /**
     * @var string Время отправки.
     */
    public string $timestamp = '';

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('message', [
            'head' => $this->head,
            'subject' => $this->subject,
            'timestamp' => $this->timestamp,
        ]);
    }
}
