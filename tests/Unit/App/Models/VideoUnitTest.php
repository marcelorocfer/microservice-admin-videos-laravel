<?php

namespace Tests\Unit\App\Models;

use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Video();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class
        ];
    }

    protected function fillables(): array
    {
        return [
            'id',
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }
}
