<?php
namespace App;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUpload
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir=$targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName= md5(uniqid()).".".$file->guessExtension();
        //die($this->getTargetDir());
        $file->move($this->getTargetDir(), $fileName);
        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
