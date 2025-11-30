<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для Domain Model Task.
 */
class TaskTest extends TestCase
{
    public function test_can_create_task_with_title(): void
    {
        $task = new Task(null, 'Test Task');

        $this->assertEquals('Test Task', $task->getTitle());
        $this->assertInstanceOf(TaskStatus::class, $task->getStatus());
        $this->assertTrue($task->getStatus()->isActive());
        $this->assertEquals('active', $task->getStatus()->getValue());
    }

    public function test_task_has_default_active_status(): void
    {
        $task = new Task(null, 'Test Task');

        $this->assertTrue($task->isActive());
        $this->assertFalse($task->isCompleted());
    }

    public function test_can_create_task_with_custom_status(): void
    {
        $task = new Task(null, 'Test Task', TaskStatus::completed());

        $this->assertTrue($task->isCompleted());
        $this->assertFalse($task->isActive());
    }

    public function test_can_toggle_task_status(): void
    {
        $task = new Task(null, 'Test Task', TaskStatus::active());

        $task->toggleStatus();
        $this->assertTrue($task->isCompleted());
        $this->assertFalse($task->isActive());

        $task->toggleStatus();
        $this->assertTrue($task->isActive());
        $this->assertFalse($task->isCompleted());
    }

    public function test_can_update_task_title(): void
    {
        $task = new Task(null, 'Original Title');

        $task->updateTitle('Updated Title');

        $this->assertEquals('Updated Title', $task->getTitle());
    }

    public function test_get_status_returns_task_status_value_object(): void
    {
        $task = new Task(null, 'Test Task', TaskStatus::completed());

        $status = $task->getStatus();
        $this->assertInstanceOf(TaskStatus::class, $status);
        $this->assertTrue($status->isCompleted());
    }

    public function test_get_status_value_returns_string_for_backward_compatibility(): void
    {
        $task = new Task(null, 'Test Task', TaskStatus::active());

        $this->assertEquals('active', $task->getStatusValue());

        $task->toggleStatus();
        $this->assertEquals('completed', $task->getStatusValue());
    }
}
