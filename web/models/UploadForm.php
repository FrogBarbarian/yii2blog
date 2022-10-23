<?php

declare(strict_types=1);

namespace app\models;
//TODO: DO IT
use yii\base\Model;
use yii\http\UploadedFile;

class UploadForm extends Model
{
    public $imageFile;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['imageFile', 'required'],
            [
                'imageFile',
                'image',
                'extensions' => 'png, jpg',
                ],
        ];
    }

    public function upload(): bool
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

            return true;
        } else {
            return false;
        }
    }
}