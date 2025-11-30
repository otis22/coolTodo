<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Models;

use PHPUnit\Framework\TestCase;
use App\Domain\Models\Task;

/**
 * Тесты для Domain Model Task.
 *
 * Этот тест заведомо провалится, пока не реализован класс Task.
 * Это отправная точка для TDD-цикла.
 */
class TaskTest extends TestCase
{
    public function test_can_create_task_with_title(): void
    {
        // Этот тест будет падать, пока не реализован класс Task
        $task = new Task(null, 'Test Task');
        
        $this->assertEquals('Test Task', $task->getTitle());
        $this->assertEquals(Task::STATUS_ACTIVE, $task->getStatus());
    }

    public function test_task_has_default_active_status(): void
    {
        $task = new Task(null, 'Test Task');
        
        $this->assertTrue($task->isActive());
        $this->assertFalse($task->isCompleted());
    }

    public function test_can_toggle_task_status(): void
    {
        $task = new Task(null, 'Test Task', Task::STATUS_ACTIVE);
        
        $task->toggleStatus();
        $this->assertTrue($task->isCompleted());
        
        $task->toggleStatus();
        $this->assertTrue($task->isActive());
    }

    public function test_can_update_task_title(): void
    {
        $task = new Task(null, 'Original Title');
        
        $task->updateTitle('Updated Title');
        
        $this->assertEquals('Updated Title', $task->getTitle());
    }
}






