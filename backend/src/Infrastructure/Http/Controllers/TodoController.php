<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Domain\UseCases\CreateTodoUseCase;
use App\Domain\UseCases\DeleteCompletedTodosUseCase;
use App\Domain\UseCases\DeleteTodoUseCase;
use App\Domain\UseCases\GetTodosUseCase;
use App\Domain\UseCases\ToggleTodoStatusUseCase;
use App\Domain\UseCases\UpdateTodoUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Контроллер для управления задачами через API.
 */
class TodoController
{
    public function __construct(
        private readonly GetTodosUseCase $getTodosUseCase,
        private readonly CreateTodoUseCase $createTodoUseCase,
        private readonly UpdateTodoUseCase $updateTodoUseCase,
        private readonly ToggleTodoStatusUseCase $toggleTodoStatusUseCase,
        private readonly DeleteTodoUseCase $deleteTodoUseCase,
        private readonly DeleteCompletedTodosUseCase $deleteCompletedTodosUseCase
    ) {
    }

    /**
     * Получить список всех задач.
     */
    public function index(): JsonResponse
    {
        $tasks = $this->getTodosUseCase->execute();

        return response()->json(
            array_map(
                fn (\App\Domain\Models\Task $task) => $this->taskToArray($task),
                $tasks
            )
        );
    }

    /**
     * Создать новую задачу.
     */
    public function store(Request $request): JsonResponse
    {
        $title = $request->input('title');
        if (!is_string($title) || $title === '') {
            return response()->json(['error' => 'Title is required'], 400);
        }

        $task = $this->createTodoUseCase->execute($title);

        return response()->json($this->taskToArray($task), 201);
    }

    /**
     * Обновить задачу.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $title = $request->input('title');
            if (!is_string($title) || $title === '') {
                return response()->json(['error' => 'Title is required'], 400);
            }

            $taskId = (int) $id;
            $task = $this->updateTodoUseCase->execute($taskId, $title);

            return response()->json($this->taskToArray($task));
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Переключить статус задачи.
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        try {
            $taskId = (int) $id;
            $task = $this->toggleTodoStatusUseCase->execute($taskId);

            return response()->json($this->taskToArray($task));
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Удалить задачу.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $taskId = (int) $id;
            $this->deleteTodoUseCase->execute($taskId);

            return response()->json(null, 204);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Удалить все выполненные задачи.
     */
    public function destroyCompleted(): JsonResponse
    {
        $deletedCount = $this->deleteCompletedTodosUseCase->execute();

        return response()->json(['deleted' => $deletedCount]);
    }

    /**
     * Преобразует Task в массив для JSON ответа.
     *
     * @param \App\Domain\Models\Task $task
     * @return array<string, mixed>
     */
    private function taskToArray(\App\Domain\Models\Task $task): array
    {
        $id = $task->getId();
        assert($id !== null, 'Task must have an ID when converting to array');

        return [
            'id' => $id,
            'title' => $task->getTitle(),
            'status' => $task->getStatus()->getValue(),
            'created_at' => null, // TODO: добавить timestamps в Task
            'updated_at' => null, // TODO: добавить timestamps в Task
        ];
    }
}
