<?php

namespace Files\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SaveCommandFactory
 * @package Files\Application\Command
 */
class SaveCommandFactory
{
    /**
     * @param UploadedFile $file
     * @param string|null $path
     * @return SaveCommandInterface
     */
    public function create(UploadedFile $file, ?string $path) : SaveCommandInterface
    {
        return new SaveCommand(
            $file,
            $path ?? bin2hex(openssl_random_pseudo_bytes(2))
        );
    }
}