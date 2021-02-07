<?php

namespace Files\Application\Service;

use Files\Application\Command\SaveCommandInterface;
use Files\Application\Dto\SaveFileDtoInterface;

/**
 * Interface FileServiceInterface
 * @package Files\Application\Service
 */
interface FileServiceInterface
{
    /**
     * @param SaveCommandInterface $command
     * @return SaveFileDtoInterface
     */
    public function save(SaveCommandInterface $command) : SaveFileDtoInterface;
}