<?php

namespace Tests\Unit\Domain\Validation;

use Throwable;
use PHPUnit\Framework\TestCase;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Exceptions\EntityValidationException;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try {
            $value = '';
            DomainValidation::notNull($value);

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testNotNullCustomException()
    {
        try {
            $value = '';
            DomainValidation::notNull($value, 'custom message error');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'custom message error');
        }
    }

    public function testStrMaxLength()
    {
        try {
            $value = 'Teste';
            DomainValidation::strMaxLength($value, 3, 'Custom Message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Message');
        }
    }

    public function testStrMinLength()
    {
        try {
            $value = 'Teste';
            DomainValidation::strMinLength($value, 8, 'Custom Message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Message');
        }
    }

    public function testStrCanNullAndMaxLength()
    {
        try {
            $value = 'Teste';
            DomainValidation::strCanNullAndMaxLength($value, 3, 'Custom Message');

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Message');
        }
    }
}