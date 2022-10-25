<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public ?UploadedFile $image = null;
    public string $signature = '';
    public string $imageName = '';

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {//TODO: Параметры загружаемых изображений
        return [
            ['image', 'required', 'message' => 'Загрузите изображение'],
            ['signature', 'required', 'message' =>  'Добавьте подпись'],
            [
                'image',
                'image',
                'extensions' => 'png, jpg',
            ],
        ];
    }

    public function upload(): bool
    {
        if ($this->validate()) {
            $this->imageName = time() . '.' . $this->image->extension;
            $this->image->saveAs('uploads/' . $this->imageName);

            return true;
        } else {
            return false;
        }
    }
}