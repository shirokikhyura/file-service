<?php

namespace FileService\Application\Dto;

use FileService\Application\Model\FileMeta;
use Symfony\Component\HttpFoundation\File\File;

interface DownloadFileDtoInterface
{
    public function getFile() : File;

    public function getFileMeta() : FileMeta;
}