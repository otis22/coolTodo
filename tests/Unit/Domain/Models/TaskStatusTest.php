<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\TaskStatus;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для Value Object TaskStatus.
 *
 * TDD: Red - этот тест будет падать, пока не реализован класс TaskStatus.
 */
class TaskStatusTest extends TestCase
{
    public function test_can_create_active_status(): void
    {
        $status = TaskStatus::active();

        $this->assertTrue($status->isActive());
        $this->assertFalse($status->isCompleted());
        $this->assertEquals('active', $status->getValue());
    }

    public function test_can_create_completed_status(): void
    {
        $status = TaskStatus::completed();

        $this->assertTrue($status->isCompleted());
        $this->assertFalse($status->isActive());
        $this->assertEquals('completed', $status->getValue());
    }

    public function test_can_create_from_string(): void
    {
        $activeStatus = TaskStatus::fromString('active');
        $this->assertTrue($activeStatus->isActive());

        $completedStatus = TaskStatus::fromString('completed');
        $this->assertTrue($completedStatus->isCompleted());
    }

    public function test_throws_exception_for_invalid_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid task status: invalid');

        TaskStatus::fromString('invalid');
    }

    public function test_status_equality(): void
    {
        $status1 = TaskStatus::active();
        $status2 = TaskStatus::active();
        $status3 = TaskStatus::completed();

        $this->assertTrue($status1->equals($status2));
        $this->assertFalse($status1->equals($status3));
    }

    public function test_can_toggle_status(): void
    {
        $active = TaskStatus::active();
        $toggled = $active->toggle();

        $this->assertTrue($toggled->isCompleted());
        $this->assertFalse($toggled->isActive());

        $backToActive = $toggled->toggle();
        $this->assertTrue($backToActive->isActive());
    }
}
