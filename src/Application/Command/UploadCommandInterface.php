<?php

namespace FileService\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface UploadCommandInterface
 * @package FileService\Application\Command
 */
interface UploadCommandInterface
{
    /**
     * GetFile.
     *
     * @return UploadedFile
     */
    public function getFile() : UploadedFile;
}