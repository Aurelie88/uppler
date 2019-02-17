<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\User;
use App\Entity\Article;
use App\ImageUpload;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUpload $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for User entities
        if ((!$entity instanceof User) && (!$entity instanceof Article)){
            return;
        }

        $file = $entity->getPicture();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }
        
        $fileName = $this->uploader->upload($file);
        $entity->setPicture($fileName);
    }
}