<?php

namespace FileService\Application\Dto;

/**
 * Class SaveFileDto
 * @package FileService\Application\Dto
 */
class SaveFileDto implements SaveFileDtoInterface
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $clientName;

    private string $fullName;

    /**
     * SaveFileDto constructor.
     * @param string $id
     * @param string $clientName
     * @param string $fullName
     */
    public function __construct(string $id, string $clientName, string $fullName)
    {
        $this->id = $id;
        $this->clientName = $clientName;
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
    public function getFullName(): string
    {
        return $this->fullName;
    }
}