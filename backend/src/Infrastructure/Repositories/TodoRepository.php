<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Domain\Repositories\TodoRepositoryInterface;
use App\Infrastructure\Models\Todo;

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
        $todos = Todo::all();
        
        return array_map(
            fn(Todo $todo) => $this->toDomainModel($todo),
            $todos->all()
        );
    }

    /**
     * Найти задачу по ID.
     *
     * @param int $id ID задачи
     * @return Task|null Задача или null, если не найдена
     */
    public function findById(int $id): ?Task
    {
        $todo = Todo::find($id);
        
        if ($todo === null) {
            return null;
        }
        
        return $this->toDomainModel($todo);
    }

    /**
     * Сохранить задачу (создать или обновить).
     *
     * @param Task $task Задача для сохранения
     * @return Task Сохраненная задача с ID
     */
    public function save(Task $task): Task
    {
        $id = $task->getId();
        
        if ($id === null) {
            // Создание новой задачи
            $todo = new Todo();
            $todo->title = $task->getTitle();
            /** @var 'active'|'completed' $statusValue */
            $statusValue = $task->getStatus()->getValue();
            $todo->status = $statusValue;
            $todo->save();
            
            return new Task($todo->id, $todo->title, TaskStatus::fromString($todo->status));
        }
        
        // Обновление существующей задачи
        $todo = Todo::findOrFail($id);
        $todo->title = $task->getTitle();
        /** @var 'active'|'completed' $statusValue */
        $statusValue = $task->getStatus()->getValue();
        $todo->status = $statusValue;
        $todo->save();
        
        return new Task($todo->id, $todo->title, TaskStatus::fromString($todo->status));
    }

    /**
     * Удалить задачу.
     *
     * @param Task $task Задача для удаления
     * @return void
     */
    public function delete(Task $task): void
    {
        $id = $task->getId();
        
        if ($id === null) {
            return;
        }
        
        Todo::destroy($id);
    }

    /**
     * Удалить все выполненные задачи.
     *
     * @return int Количество удаленных задач
     */
    public function deleteCompleted(): int
    {
        /** @var int $deletedCount */
        $deletedCount = Todo::where('status', 'completed')->delete();
        return $deletedCount;
    }

    /**
     * Преобразовать Eloquent модель в Domain модель.
     *
     * @param Todo $todo Eloquent модель
     * @return Task Domain модель
     */
    private function toDomainModel(Todo $todo): Task
    {
        return new Task(
            $todo->id,
            $todo->title,
            TaskStatus::fromString($todo->status)
        );
    }
}





