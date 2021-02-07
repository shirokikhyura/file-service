<?php

namespace Files;

use Files\Infrastructure\DependencyInjection\FilesExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FilesBundle
 * @package Files
 */
class FilesBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new FilesExtension();
    }
}