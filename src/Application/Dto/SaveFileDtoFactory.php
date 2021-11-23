<?php

namespace FileService\Application\Dto;

/**
 * Class SaveFileDtoFactory
 * @package FileService\Application\Dto
 */
class SaveFileDtoFactory
{
    /**
     * @param array $arguments
     * @return SaveFileDtoInterface
     */
    public function create(array $arguments) : SaveFileDtoInterface
    {
        return new SaveFileDto(
            $arguments['id'] ?? '',
            $arguments['clientName'] ?? '',
            $arguments['name'] ?? '',
        );
    }
}