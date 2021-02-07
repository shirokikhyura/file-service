<?php

namespace Files\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SaveCommand
 * @package Files\Application\Command
 */
class SaveCommand implements SaveCommandInterface
{
    /**
     * @var UploadedFile
     */
    private UploadedFile $file;

    /**
     * @var string
     */
    private string $path;

    /**
     * SaveCommand constructor.
     * @param UploadedFile $file
     * @param string $path
     */
    public function __construct(UploadedFile $file, string $path)
    {
        $this->file = $file;
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getFile() : UploadedFile
    {
        return $this->file;
    }

    /**
     * @inheritDoc
     */
    public function getPath() : string
    {
        return $this->path;
    }
}