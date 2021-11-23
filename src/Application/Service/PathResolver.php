<?php

namespace FileService\Application\Service;

use SplFileInfo;

class PathResolver implements PathResolverInterface
{
    private SplFileInfo $path;

    private ?PathResolverInterface $next;

    /**
     * PathResolver constructor.
     * @param string $path
     * @param PathResolverInterface|null $next
     */
    public function __construct(string $path, ?PathResolverInterface $next = null)
    {
        $this->path = new SplFileInfo($path);
        $this->next = $next;
    }

    public function getPath(): string
    {
        if ($this->path->isDir() && $this->path->isWritable()) {
            return $this->path->getRealPath();
        }

        if (null === $this->next) {
            throw new \RuntimeException("Can't access to save path.");
        }

        return $this->next->getPath();
    }
}
