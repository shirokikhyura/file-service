<?php

namespace Files\Application\Dto;

/**
 * Interface SaveFileDtoInterface
 * @package Files\Application\Dto
 */
interface SaveFileDtoInterface
{
    /**
     * @return string
     */
    public function getHost() : string;

    /**
     * @return string
     */
    public function getPath() : string;

    /**
     * @return string
     */
    public function getFileName() : string;
}