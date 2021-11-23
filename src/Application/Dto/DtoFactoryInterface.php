<?php

namespace FileService\Application\Dto;

interface DtoFactoryInterface
{
    public function create(array $arguments) : object;
}