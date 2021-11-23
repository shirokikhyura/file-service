<?php

namespace FileService\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadCommandFactory
 * @package FileService\Application\Command
 */
class UploadCommandFactory
{
    /**
     * @param UploadedFile $file
     * @return UploadCommandInterface
     */
    public function create(UploadedFile $file) : UploadCommandInterface
    {
        return new UploadCommand($file);
    }
}