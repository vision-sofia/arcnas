<?php

namespace App\Entity;

interface UploadableFileInterface
{
    public function getFile();

    public function setFile($filename): void;
}
