<?php

namespace Tests\Stubs;

use Core\UseCase\Interfaces\FileStorageInterface;

class UploadFilesStub implements FileStorageInterface
{
    /**
     * @param string $path
     * @param array $file
     * @return string
     */
    public function store(string $path, array $file): string
    {
        return "{$path}/test.mp4";
    }

    public function delete(string $path)
    {

    }
}
