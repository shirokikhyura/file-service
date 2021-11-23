<?php

namespace FileService\Application\Dto;

/**
 * Interface SaveFileDtoInterface
 * @package Application\Dto
 */
interface SaveFileDtoInterface
{
    /**
     * @return string
     */
    public function getId() : string;

    /**
     * @return string
     */
    public function getClientName() : string;

    /**
     * @return string
     */
    public function getFullName() : string;
}