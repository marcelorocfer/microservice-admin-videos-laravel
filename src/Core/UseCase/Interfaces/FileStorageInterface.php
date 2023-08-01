<?php

namespace Core\UseCase\Interfaces;

interface FileStorageInterface
{
    /**
     * @param string $path
     * @param array $file
     * @return string
     */
    public function store(string $path, array $file): string;
    public function delete(string $path);
}
