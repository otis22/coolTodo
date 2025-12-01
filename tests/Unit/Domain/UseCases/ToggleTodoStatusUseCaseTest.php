<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Domain\Repositories\TodoRepositoryInterface;
use App\Domain\UseCases\ToggleTodoStatusUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для ToggleTodoStatusUseCase.
 */
class ToggleTodoStatusUseCaseTest extends TestCase
{
    private TodoRepositoryInterface&MockObject $repository;
    private ToggleTodoStatusUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(TodoRepositoryInterface::class);
        $this->useCase = new ToggleTodoStatusUseCase($this->repository);
    }

    public function test_execute_toggles_status_from_active_to_completed(): void
    {
        $taskId = 1;
        $task = new Task($taskId, 'Test Task', TaskStatus::active());
        $toggledTask = new Task($taskId, 'Test Task', TaskStatus::completed());

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($taskId)
            ->willReturn($task);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Task $task) {
                return $task->getStatus()->isCompleted();
            }))
            ->willReturn($toggledTask);

        $result = $this->useCase->execute($taskId);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($taskId, $result->getId());
        $this->assertTrue($result->getStatus()->isCompleted());
    }

    public function test_execute_toggles_status_from_completed_to_active(): void
    {
        $taskId = 1;
        $task = new Task($taskId, 'Test Task', TaskStatus::completed());
        $toggledTask = new Task($taskId, 'Test Task', TaskStatus::active());

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($taskId)
            ->willReturn($task);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Task $task) {
                return $task->getStatus()->isActive();
            }))
            ->willReturn($toggledTask);

        $result = $this->useCase->execute($taskId);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($taskId, $result->getId());
        $this->assertTrue($result->getStatus()->isActive());
    }

    public function test_execute_throws_exception_when_task_not_found(): void
    {
        $taskId = 999;

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($taskId)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Task with ID {$taskId} not found");

        $this->useCase->execute($taskId);
    }
}
