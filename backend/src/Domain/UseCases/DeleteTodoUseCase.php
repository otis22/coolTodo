<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Use Case для удаления задачи.
 */
class DeleteTodoUseCase
{
    public function __construct(
        private readonly TodoRepositoryInterface $repository
    ) {
    }

    /**
     * Удаляет задачу по ID.
     *
     * @param int $id ID задачи
     * @return void
     * @throws \DomainException Если задача не найдена
     */
    public function execute(int $id): void
    {
        $task = $this->repository->findById($id);

        if ($task === null) {
            throw new \DomainException("Task with ID {$id} not found");
        }

        $this->repository->delete($task);
    }
}
