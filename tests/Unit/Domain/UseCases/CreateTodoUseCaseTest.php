<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Domain\Repositories\TodoRepositoryInterface;
use App\Domain\UseCases\CreateTodoUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для CreateTodoUseCase.
 */
class CreateTodoUseCaseTest extends TestCase
{
    private TodoRepositoryInterface&MockObject $repository;
    private CreateTodoUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(TodoRepositoryInterface::class);
        $this->useCase = new CreateTodoUseCase($this->repository);
    }

    public function test_execute_creates_task_with_active_status(): void
    {
        $title = 'New Task';
        $savedTask = new Task(1, $title, TaskStatus::active());

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Task $task) use ($title) {
                return $task->getTitle() === $title
                    && $task->getId() === null
                    && $task->getStatus()->isActive();
            }))
            ->willReturn($savedTask);

        $result = $this->useCase->execute($title);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals($title, $result->getTitle());
        $this->assertTrue($result->getStatus()->isActive());
    }

    public function test_execute_returns_saved_task_with_id(): void
    {
        $title = 'Test Task';
        $savedTask = new Task(42, $title, TaskStatus::active());

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->willReturn($savedTask);

        $result = $this->useCase->execute($title);

        $this->assertEquals(42, $result->getId());
        $this->assertEquals($title, $result->getTitle());
    }
}
