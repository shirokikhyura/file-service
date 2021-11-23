<?php

namespace FileService\Application\Service;

class TempPathResolver extends PathResolver
{
    public function __construct(?PathResolverInterface $next = null)
    {
        parent::__construct(sys_get_temp_dir(), $next);
    }
}