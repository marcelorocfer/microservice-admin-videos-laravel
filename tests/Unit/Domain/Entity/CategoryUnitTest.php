<?php

namespace Tests\Unit\Domain\Entity;

use Throwable;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use Core\Domain\Exceptions\EntityValidationException;

class CategoryUnitTest extends TestCase
{
    public function testAttributes() 
    {
        $category = new Category(
            name: 'New Category',
            description: 'New Description',
            is_active: true,
        );

        $this->assertNotEmpty($category->id());
        $this->assertNotEmpty($category->created_at());
        $this->assertEquals('New Category', $category->name);
        $this->assertEquals('New Description', $category->description);
        $this->assertEquals(true, $category->is_active);
    }

    public function testIsActivated()
    {
        $category = new Category(
            name: 'New Category',
            is_active: false,
        );

        $this->assertFalse($category->is_active);
        $category->activate();
        $this->assertTrue($category->is_active);
    }

    public function testIsDeactivated()
    {
        $category = new Category(
            name: 'New Category',
        );

        $this->assertTrue($category->is_active);
        $category->disable();
        $this->assertFalse($category->is_active);
    }   
    
    public function testUpdate()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $category = new Category(
            id: $uuid,
            name: 'Category',
            description: 'Description',
            is_active: true,
            created_at: '2023-06-14 10:00:00',
        );

        $category->update(
            name: 'New Name',
            description: 'New Description',
        );

        $this->assertEquals($uuid, $category->id());
        $this->assertEquals('New Name', $category->name);
        $this->assertEquals('New Description', $category->description);
    }

    public function testExceptionName()
    {
        try {
            new Category(
                name: 'Ne',
                description: 'New Description',
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testExceptionDescription()
    {
        try {
            new Category(
                name: 'New Name',
                description: random_bytes(999999)
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}