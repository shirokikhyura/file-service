<?php


namespace FileService\Application\Dto;


use FileService\Application\Model\FileMeta;
use Symfony\Component\HttpFoundation\File\File;

class DownloadFileDto implements DownloadFileDtoInterface
{
    private File $file;

    private FileMeta $fileMeta;

    /**
     * DownloadFileDto constructor.
     * @param File $file
     * @param FileMeta $fileMeta
     */
    public function __construct(File $file, FileMeta $fileMeta)
    {
        $this->file = $file;
        $this->fileMeta = $fileMeta;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function getFileMeta(): FileMeta
    {
        return $this->fileMeta;
    }
}