<?php


namespace FileService\Application\Dto;

use FileService\Application\Model\FileMeta;
use Symfony\Component\HttpFoundation\File\File;
use RuntimeException;

class DownloadFileDtoFactory implements DtoFactoryInterface
{
    public function create(array $arguments): DownloadFileDtoInterface
    {
        if (!isset($arguments['meta']) && !($arguments['meta'] instanceof FileMeta)) {
            throw new RuntimeException("File meta is not defined");
        }

        return new DownloadFileDto(
            new File($arguments['path']),
            $arguments['meta']
        );
    }
}