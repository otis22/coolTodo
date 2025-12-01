<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Domain\Repositories\TodoRepositoryInterface;
use App\Domain\UseCases\UpdateTodoUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для UpdateTodoUseCase.
 */
class UpdateTodoUseCaseTest extends TestCase
{
    private TodoRepositoryInterface&MockObject $repository;
    private UpdateTodoUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(TodoRepositoryInterface::class);
        $this->useCase = new UpdateTodoUseCase($this->repository);
    }

    public function test_execute_updates_task_title(): void
    {
        $taskId = 1;
        $oldTitle = 'Old Title';
        $newTitle = 'New Title';
        $existingTask = new Task($taskId, $oldTitle, TaskStatus::active());
        $updatedTask = new Task($taskId, $newTitle, TaskStatus::active());

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($taskId)
            ->willReturn($existingTask);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Task $task) use ($taskId, $newTitle) {
                return $task->getId() === $taskId
                    && $task->getTitle() === $newTitle;
            }))
            ->willReturn($updatedTask);

        $result = $this->useCase->execute($taskId, $newTitle);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($taskId, $result->getId());
        $this->assertEquals($newTitle, $result->getTitle());
    }

    public function test_execute_throws_exception_when_task_not_found(): void
    {
        $taskId = 999;
        $newTitle = 'New Title';

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

        $this->useCase->execute($taskId, $newTitle);
    }

    public function test_execute_preserves_task_status(): void
    {
        $taskId = 1;
        $newTitle = 'Updated Title';
        $existingTask = new Task($taskId, 'Old Title', TaskStatus::completed());
        $updatedTask = new Task($taskId, $newTitle, TaskStatus::completed());

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($taskId)
            ->willReturn($existingTask);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->willReturn($updatedTask);

        $result = $this->useCase->execute($taskId, $newTitle);

        $this->assertTrue($result->getStatus()->isCompleted());
    }
}

