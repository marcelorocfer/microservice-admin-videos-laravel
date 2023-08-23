<?php

namespace Tests\Feature\App\Services;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Services\Storage\FileStorage;
use Illuminate\Support\Facades\Storage;

class FileStorageTest extends TestCase
{
    public function test_store()
    {
        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');

        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $filePath = (new FileStorage())
                        ->store('videos', $file);

        Storage::assertExists($filePath);
    }
}
