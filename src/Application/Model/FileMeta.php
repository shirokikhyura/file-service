<?php

namespace FileService\Application\Model;

class FileMeta
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $path;
    /**
     * @var string
     */
    private string $clientName;
    /**
     * @var string
     */
    private string $clientExtension;

    /**
     * FileMeta constructor.
     * @param string $name
     * @param string $path
     * @param string $clientName
     * @param string $clientExtension
     */
    public function __construct(string $name, string $path, string $clientName, string $clientExtension)
    {
        $this->name = $name;
        $this->path = $path;
        $this->clientName = $clientName;
        $this->clientExtension = $clientExtension;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * @return string
     */
    public function getClientExtension(): string
    {
        return $this->clientExtension;
    }
}