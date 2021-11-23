<?php

namespace FileService\Application\Service;

use FileService\Application\Command\UploadCommandInterface;
use FileService\Application\Dto\DownloadFileDtoInterface;
use FileService\Application\Dto\SaveFileDtoInterface;

interface FileServiceInterface
{
    public function upload(UploadCommandInterface $command) : SaveFileDtoInterface;

    public function download(string $fileName) : DownloadFileDtoInterface;

    public function get(string $fullFileName) : DownloadFileDtoInterface;
}