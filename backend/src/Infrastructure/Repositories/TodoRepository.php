<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Models\Task;
use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Репозиторий для работы с задачами в БД.
 */
class TodoRepository implements TodoRepositoryInterface
{
    /**
     * Получить все задачи.
     *
     * @return array<Task>
     */
    public function findAll(): array
    {
        // @todo PDD: Реализовать через Eloquent после создания миграции
        // Пока возвращаем пустой массив
        return [];
    }

    // @todo PDD:1h Реализовать остальные методы TodoRepository
    // - findById(int $id): ?Task
    // - save(Task $task): Task
    // - delete(Task $task): void
    // - deleteCompleted(): int
}





