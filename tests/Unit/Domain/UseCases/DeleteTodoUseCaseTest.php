<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Domain\Repositories\TodoRepositoryInterface;
use App\Domain\UseCases\DeleteTodoUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для DeleteTodoUseCase.
 */
class DeleteTodoUseCaseTest extends TestCase
{
    private TodoRepositoryInterface&MockObject $repository;
    private DeleteTodoUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(TodoRepositoryInterface::class);
        $this->useCase = new DeleteTodoUseCase($this->repository);
    }

    public function test_execute_deletes_task(): void
    {
        $taskId = 1;
        $task = new Task($taskId, 'Test Task', TaskStatus::active());

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($taskId)
            ->willReturn($task);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($task);

        $this->useCase->execute($taskId);
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
            ->method('delete');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Task with ID {$taskId} not found");

        $this->useCase->execute($taskId);
    }
}

