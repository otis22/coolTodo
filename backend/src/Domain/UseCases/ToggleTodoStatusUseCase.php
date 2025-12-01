<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Use Case для изменения статуса задачи.
 */
class ToggleTodoStatusUseCase
{
    public function __construct(
        private readonly TodoRepositoryInterface $repository
    ) {
    }

    /**
     * Переключает статус задачи (active ↔ completed).
     *
     * @param int $id ID задачи
     * @return Task Обновленная задача
     * @throws \DomainException Если задача не найдена
     */
    public function execute(int $id): Task
    {
        $task = $this->repository->findById($id);

        if ($task === null) {
            throw new \DomainException("Task with ID {$id} not found");
        }

        $task->toggleStatus();
        return $this->repository->save($task);
    }
}
