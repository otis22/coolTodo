<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Use Case для удаления всех выполненных задач.
 */
class DeleteCompletedTodosUseCase
{
    public function __construct(
        private readonly TodoRepositoryInterface $repository
    ) {
    }

    /**
     * Удаляет все задачи со статусом 'completed' и возвращает количество удаленных.
     *
     * @return int Количество удаленных задач
     */
    public function execute(): int
    {
        return $this->repository->deleteCompleted();
    }
}
