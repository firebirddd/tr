<?php
namespace app\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
     return [
        [['image'], 'required'],
        [['image'], 'file', 'extensions' => 'jpg,jpeg,png']
     ];
    }


    public function uploadFile(UploadedFile $file, $currentImage)
    {
        $this->image = $file;

        if(!$this->validate())
            return false;

        $this->deleteCurrentImage($currentImage);
        $fileName = $this->saveImage();

        return $fileName;
    }


    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }


    private function generateFilename()
    {
        return strtolower(md5(uniqId($this->image->baseName)) . '.' . $this->image->extension);
    }


    public function deleteCurrentImage($currentImage)
    {
        $currentImageFullPath = $this->getFolder() . $currentImage;

        if($this->isFileExists($currentImageFullPath, $currentImage))
            unlink($currentImageFullPath);
    }


    private function isFileExists($imageFullPath, $imageName)
    {
        if(!$imageFullPath || !$imageName)
            return false;

        if(file_exists($imageFullPath))
            return true;
        return false;
    }


    private function saveImage()
    {
        $fileName = $this->generateFilename();
        $fullPath = $this->getFolder() . $fileName;

        $this->image->saveAs($fullPath);

        return $fileName;
    }
}