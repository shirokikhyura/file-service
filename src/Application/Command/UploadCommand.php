<?php

namespace FileService\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadCommand
 * @package FileService\Application\Command
 */
class UploadCommand implements UploadCommandInterface
{
    /**
     * @var UploadedFile
     */
    private UploadedFile $file;

    /**
     * SaveCommand constructor.
     * @param UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function getFile() : UploadedFile
    {
        return $this->file;
    }
}