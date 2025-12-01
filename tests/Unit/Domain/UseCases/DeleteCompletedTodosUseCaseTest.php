<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Repositories\TodoRepositoryInterface;
use App\Domain\UseCases\DeleteCompletedTodosUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для DeleteCompletedTodosUseCase.
 */
class DeleteCompletedTodosUseCaseTest extends TestCase
{
    private TodoRepositoryInterface&MockObject $repository;
    private DeleteCompletedTodosUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(TodoRepositoryInterface::class);
        $this->useCase = new DeleteCompletedTodosUseCase($this->repository);
    }

    public function test_execute_deletes_completed_tasks_and_returns_count(): void
    {
        $deletedCount = 3;

        $this->repository
            ->expects($this->once())
            ->method('deleteCompleted')
            ->willReturn($deletedCount);

        $result = $this->useCase->execute();

        $this->assertEquals($deletedCount, $result);
    }

    public function test_execute_returns_zero_when_no_completed_tasks(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('deleteCompleted')
            ->willReturn(0);

        $result = $this->useCase->execute();

        $this->assertEquals(0, $result);
    }
}
