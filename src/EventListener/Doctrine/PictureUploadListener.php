<?php

namespace App\EventListener\Doctrine;

use App\Entity\UploadableFileInterface;
use App\Services\Upload\UploadService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureUploadListener
{
    private $uploader;
    private $filesystem;

    public function __construct(UploadService $uploader, Filesystem $filesystem)
    {
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        /** @var UploadableFileInterface $entity */
        $entity = $args->getEntity();

        if (!$entity instanceof UploadableFileInterface) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            if (!$this->filesystem->exists($this->uploader->getTargetDirectory() . '/' . $fileName)) {
                $entity->setFile(null);
            } else {
                $entity->setFile(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
            }
        }
    }

    private function uploadFile($entity): void
    {

        if (!$entity instanceof UploadableFileInterface) {
            return;
        }

        /** @var UploadableFileInterface $entity */
        $file = $entity->getFile();

        if ($file instanceof UploadedFile && !$this->filesystem->exists($this->uploader->getTargetDirectory() . '/' . $file->getFilename())) {
            $fileName = $this->uploader->upload($file);

            $entity->setFile($fileName);
        } elseif ($file instanceof File) {
            $entity->setFile($file->getFilename());
        }
    }
}
