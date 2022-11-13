<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

/**
 * Форма загрузки изображений.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile|null Изображение.
     */
    public ?UploadedFile $image = null;
    /**
     * @var string Подпись.
     */
    public string $signature = '';
    /**
     * @var string Имя файла.
     */
    public string $imageName = '';

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['image', 'required', 'message' => 'Загрузите изображение'],
            ['signature', 'required', 'message' =>  'Добавьте подпись'],
            [
                'image',
                'image',
                'extensions' => 'png, jpg',
                'wrongExtension' => 'Изображение должно быть формата .jpg или .png',
            ],
        ];
    }

    /**
     * Загружает изображение на сервер.
     */
    public function upload(): bool
    {
        if ($this->validate()) {
            $username = Yii::$app
                ->user
                ->getIdentity()
                ->getUsername();
            $this->imageName = "{$username}_" .  time() . ".{$this->image->extension}";
            $this
                ->image
                ->saveAs('uploads/' . $this->imageName);

            return true;
        } else {
            return false;
        }
    }
}
