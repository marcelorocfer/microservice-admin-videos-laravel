<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryUnitTest extends TestCase
{
    protected function model(): Model
    {
        return new Category();
    }

    public function testIfUseTraits()
    {
        $traitsNeed = [
            HasFactory::class,
            SoftDeletes::class
        ];

        $traitsUsed = array_keys(class_uses($this->model()));

        $this->assertEquals($traitsNeed, $traitsUsed);
    }

    public function testIncrementingIsFalse()
    {
        $model = $this->model();
        $this->assertFalse($model->incrementing);
    }

    public function testFillables()
    {
        $expectedFillable = [
            'id',
            'name',
            'description',
            'is_active',
        ];

        $fillable = $this->model()->getFillable();

        $this->assertEquals($expectedFillable, $fillable);
    }

    public function testHasCasts()
    {
        $expectedCasts = [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];

        $casts = $this->model()->getCasts();

        $this->assertEquals($expectedCasts, $casts);
    }
}
