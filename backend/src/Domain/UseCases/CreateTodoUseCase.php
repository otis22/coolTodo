<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Use Case для создания новой задачи.
 */
class CreateTodoUseCase
{
    public function __construct(
        private readonly TodoRepositoryInterface $repository
    ) {
    }

    /**
     * Создает новую задачу со статусом 'active'.
     *
     * @param string $title Название задачи
     * @return Task Созданная задача с ID
     */
    public function execute(string $title): Task
    {
        $task = new Task(null, $title, TaskStatus::active());

        return $this->repository->save($task);
    }
}
