<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Tests\TestCase;
use App\Domain\Models\Task;
use App\Domain\Models\TaskStatus;
use App\Infrastructure\Repositories\TodoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Тесты для TodoRepository.
 *
 * TDD: Red - эти тесты будут падать, пока не реализован репозиторий.
 */
class TodoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TodoRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TodoRepository();
    }

    public function test_find_all_returns_empty_array_when_no_tasks(): void
    {
        $result = $this->repository->findAll();

        $this->assertEmpty($result);
    }

    public function test_find_all_returns_all_tasks(): void
    {
        // Создаем задачи
        $task1 = new Task(null, 'Task 1');
        $task2 = new Task(null, 'Task 2', TaskStatus::completed());
        
        $this->repository->save($task1);
        $this->repository->save($task2);

        $result = $this->repository->findAll();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Task::class, $result[0]);
        $this->assertInstanceOf(Task::class, $result[1]);
    }

    public function test_find_by_id_returns_null_when_not_found(): void
    {
        $result = $this->repository->findById(999);

        $this->assertNull($result);
    }

    public function test_find_by_id_returns_task_when_found(): void
    {
        $task = new Task(null, 'Test Task');
        $saved = $this->repository->save($task);
        $id = $saved->getId();

        $this->assertNotNull($id);
        
        $found = $this->repository->findById($id);

        $this->assertInstanceOf(Task::class, $found);
        $this->assertEquals($id, $found->getId());
        $this->assertEquals('Test Task', $found->getTitle());
    }

    public function test_save_creates_new_task(): void
    {
        $task = new Task(null, 'New Task');

        $saved = $this->repository->save($task);

        $this->assertInstanceOf(Task::class, $saved);
        $this->assertNotNull($saved->getId());
        $this->assertEquals('New Task', $saved->getTitle());
        $this->assertTrue($saved->getStatus()->isActive());
    }

    public function test_save_updates_existing_task(): void
    {
        $task = new Task(null, 'Original Title');
        $saved = $this->repository->save($task);
        $id = $saved->getId();

        $this->assertNotNull($id);

        $saved->updateTitle('Updated Title');
        $updated = $this->repository->save($saved);

        $this->assertEquals($id, $updated->getId());
        $this->assertEquals('Updated Title', $updated->getTitle());
    }

    public function test_save_preserves_task_status(): void
    {
        $task = new Task(null, 'Task', TaskStatus::completed());

        $saved = $this->repository->save($task);

        $this->assertTrue($saved->getStatus()->isCompleted());
    }

    public function test_delete_removes_task(): void
    {
        $task = new Task(null, 'Task to delete');
        $saved = $this->repository->save($task);
        $id = $saved->getId();

        $this->assertNotNull($id);
        $this->assertNotNull($this->repository->findById($id));

        $this->repository->delete($saved);

        $this->assertNull($this->repository->findById($id));
    }

    public function test_delete_completed_removes_only_completed_tasks(): void
    {
        $active1 = new Task(null, 'Active 1');
        $active2 = new Task(null, 'Active 2');
        $completed1 = new Task(null, 'Completed 1', TaskStatus::completed());
        $completed2 = new Task(null, 'Completed 2', TaskStatus::completed());

        $savedActive1 = $this->repository->save($active1);
        $savedActive2 = $this->repository->save($active2);
        $this->repository->save($completed1);
        $this->repository->save($completed2);

        $deleted = $this->repository->deleteCompleted();

        $this->assertEquals(2, $deleted);
        $this->assertCount(2, $this->repository->findAll());
        
        $active1Id = $savedActive1->getId();
        $active2Id = $savedActive2->getId();
        $this->assertNotNull($active1Id);
        $this->assertNotNull($active2Id);
        $this->assertNotNull($this->repository->findById($active1Id));
        $this->assertNotNull($this->repository->findById($active2Id));
    }

    public function test_delete_completed_returns_zero_when_no_completed_tasks(): void
    {
        $active = new Task(null, 'Active');
        $this->repository->save($active);

        $deleted = $this->repository->deleteCompleted();

        $this->assertEquals(0, $deleted);
        $this->assertCount(1, $this->repository->findAll());
    }
}

