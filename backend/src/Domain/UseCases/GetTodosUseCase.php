<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Task;
use App\Domain\Repositories\TodoRepositoryInterface;

/**
 * Use Case для получения списка всех задач.
 */
class GetTodosUseCase
{
    public function __construct(
        private readonly TodoRepositoryInterface $repository
    ) {
    }

    /**
     * Получить все задачи.
     *
     * @return array<Task>
     */
    public function execute(): array
    {
        return $this->repository->findAll();
    }
}





