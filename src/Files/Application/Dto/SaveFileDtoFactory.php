<?php

namespace Files\Application\Dto;

/**
 * Class SaveFileDtoFactory
 * @package Files\Application\Dto
 */
class SaveFileDtoFactory
{
    /**
     * @var string
     */
    private string $defaultHost;

    /**
     * SaveFileDtoFactory constructor.
     * @param string $defaultHost
     */
    public function __construct(string $defaultHost)
    {
        $this->defaultHost = $defaultHost;
    }

    /**
     * @param array $arguments
     * @return SaveFileDtoInterface
     */
    public function create(array $arguments) : SaveFileDtoInterface
    {
        return new SaveFileDto(
            $arguments['host'] ?? $this->defaultHost,
            $arguments['path'] ?? '',
            $arguments['fileName'] ?? '',
        );
    }
}