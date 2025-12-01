<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Infrastructure\Repositories\TodoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature тесты для TodoController API endpoints.
 */
class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    private TodoRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(TodoRepository::class);
    }

    public function test_index_returns_all_todos(): void
    {
        $task1 = new Task(null, 'Task 1', TaskStatus::active());
        $task2 = new Task(null, 'Task 2', TaskStatus::completed());
        $this->repository->save($task1);
        $this->repository->save($task2);

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['title' => 'Task 1'])
            ->assertJsonFragment(['title' => 'Task 2']);
    }

    public function test_index_returns_empty_array_when_no_todos(): void
    {
        $response = $this->getJson('/api/todos');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function test_store_creates_new_todo(): void
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'New Task',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'New Task',
                'status' => 'active',
            ])
            ->assertJsonStructure([
                'id',
                'title',
                'status',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_update_updates_todo_title(): void
    {
        $task = new Task(null, 'Old Title', TaskStatus::active());
        $savedTask = $this->repository->save($task);

        $response = $this->putJson("/api/todos/{$savedTask->getId()}", [
            'title' => 'New Title',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $savedTask->getId(),
                'title' => 'New Title',
                'status' => 'active',
            ]);
    }

    public function test_update_returns_404_when_todo_not_found(): void
    {
        $response = $this->putJson('/api/todos/999', [
            'title' => 'New Title',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_status_toggles_todo_status(): void
    {
        $task = new Task(null, 'Test Task', TaskStatus::active());
        $savedTask = $this->repository->save($task);
        $taskId = $savedTask->getId();
        assert($taskId !== null, 'Saved task must have an ID');

        $response = $this->patchJson("/api/todos/{$taskId}/status");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $taskId,
                'status' => 'completed',
            ]);
    }

    public function test_update_status_returns_404_when_todo_not_found(): void
    {
        $response = $this->patchJson('/api/todos/999/status');

        $response->assertStatus(404);
    }

    public function test_destroy_deletes_todo(): void
    {
        $task = new Task(null, 'Test Task', TaskStatus::active());
        $savedTask = $this->repository->save($task);
        $taskId = $savedTask->getId();
        assert($taskId !== null, 'Saved task must have an ID');

        $response = $this->deleteJson("/api/todos/{$taskId}");

        $response->assertStatus(204);

        $this->assertNull($this->repository->findById($taskId));
    }

    public function test_destroy_returns_404_when_todo_not_found(): void
    {
        $response = $this->deleteJson('/api/todos/999');

        $response->assertStatus(404);
    }

    public function test_destroy_completed_deletes_all_completed_todos(): void
    {
        $task1 = new Task(null, 'Task 1', TaskStatus::active());
        $task2 = new Task(null, 'Task 2', TaskStatus::completed());
        $task3 = new Task(null, 'Task 3', TaskStatus::completed());
        $savedTask1 = $this->repository->save($task1);
        $savedTask2 = $this->repository->save($task2);
        $savedTask3 = $this->repository->save($task3);
        $task1Id = $savedTask1->getId();
        $task2Id = $savedTask2->getId();
        $task3Id = $savedTask3->getId();
        assert($task1Id !== null && $task2Id !== null && $task3Id !== null, 'Saved tasks must have IDs');

        $response = $this->deleteJson('/api/todos/completed');

        $response->assertStatus(200)
            ->assertJson([
                'deleted' => 2,
            ]);

        $this->assertNotNull($this->repository->findById($task1Id));
        $this->assertNull($this->repository->findById($task2Id));
        $this->assertNull($this->repository->findById($task3Id));
    }

    public function test_destroy_completed_returns_zero_when_no_completed_todos(): void
    {
        $response = $this->deleteJson('/api/todos/completed');

        $response->assertStatus(200)
            ->assertJson([
                'deleted' => 0,
            ]);
    }
}

