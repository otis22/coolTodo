<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Task;

/**
 * Интерфейс репозитория для работы с задачами.
 */
interface TodoRepositoryInterface
{
    /**
     * Получить все задачи.
     *
     * @return array<Task>
     */
    public function findAll(): array;
}

