<?php

namespace Files\Application\Dto;

/**
 * Class SaveFileDto
 * @package Files\Application\Dto
 */
class SaveFileDto implements SaveFileDtoInterface
{
    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $fileName;

    /**
     * SaveFileDto constructor.
     * @param string $host
     * @param string $path
     * @param string $fileName
     */
    public function __construct(string $host, string $path, string $fileName)
    {
        $this->host = $host;
        $this->path = $path;
        $this->fileName = $fileName;
    }

    /**
     * @inheritDoc
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getFileName() : string
    {
        return $this->fileName;
    }
}