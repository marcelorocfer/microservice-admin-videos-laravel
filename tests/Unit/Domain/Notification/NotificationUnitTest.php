<?php

namespace Tests\Unit\Domain\Notification;

use PHPUnit\Framework\TestCase;
use Core\Domain\Notification\Notification;

class NotificationUnitTest extends TestCase
{
    public function testGetErrors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddErrors()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $errors = $notification->getErrors();

        $this->assertCount(1, $errors);
    }
}
