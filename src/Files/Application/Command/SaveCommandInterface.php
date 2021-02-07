<?php

namespace Files\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface SaveCommandInterface
 * @package Files\Application\Command
 */
interface SaveCommandInterface
{
    /**
     * @return UploadedFile
     */
    public function getFile() : UploadedFile;

    /**
     * @return string
     */
    public function getPath() : string;
}