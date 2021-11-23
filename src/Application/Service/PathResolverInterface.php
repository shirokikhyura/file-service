<?php

namespace FileService\Application\Service;

interface PathResolverInterface
{
    /**
     * @return string
     */
    public function getPath() : string;
}