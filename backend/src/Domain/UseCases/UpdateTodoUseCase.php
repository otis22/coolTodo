<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Use Case для обновления задачи.
 */
class UpdateTodoUseCase
{
    public function __construct(
        private readonly TodoRepositoryInterface $repository
    ) {
    }

    /**
     * Обновляет title задачи.
     *
     * @param int $id ID задачи
     * @param string $title Новый title задачи
     * @return Task Обновленная задача
     * @throws \DomainException Если задача не найдена
     */
    public function execute(int $id, string $title): Task
    {
        $task = $this->repository->findById($id);

        if ($task === null) {
            throw new \DomainException("Task with ID {$id} not found");
        }

        $task->updateTitle($title);
        return $this->repository->save($task);
    }
}
