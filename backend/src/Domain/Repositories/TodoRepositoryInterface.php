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

    /**
     * Найти задачу по ID.
     *
     * @param int $id ID задачи
     * @return Task|null Задача или null, если не найдена
     */
    public function findById(int $id): ?Task;

    /**
     * Сохранить задачу (создать или обновить).
     *
     * @param Task $task Задача для сохранения
     * @return Task Сохраненная задача с ID
     */
    public function save(Task $task): Task;

    /**
     * Удалить задачу.
     *
     * @param Task $task Задача для удаления
     * @return void
     */
    public function delete(Task $task): void;

    /**
     * Удалить все выполненные задачи.
     *
     * @return int Количество удаленных задач
     */
    public function deleteCompleted(): int;
}

