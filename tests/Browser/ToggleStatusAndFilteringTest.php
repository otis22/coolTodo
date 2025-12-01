<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E тест для переключения статуса и фильтрации задач.
 */
class ToggleStatusAndFilteringTest extends DuskTestCase
{
    /**
     * Тест переключения статуса задачи через checkbox.
     */
    public function test_user_can_toggle_todo_status(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем задачу
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Task To Toggle')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->assertSee('Task To Toggle')
                ->assertSee('1 item left');

            // Переключаем статус на completed
            $browser->check('.todo-checkbox')
                ->pause(500)
                ->assertSee('0 items left')
                ->assertPresent('.todo-item.completed');

            // Переключаем обратно на active
            $browser->uncheck('.todo-checkbox')
                ->pause(500)
                ->assertSee('1 item left')
                ->assertMissing('.todo-item.completed');
        });
    }

    /**
     * Тест фильтрации задач (All/Active/Completed).
     */
    public function test_user_can_filter_todos(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем несколько задач
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Active Task 1')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->type('input[placeholder="What needs to be done?"]', 'Active Task 2')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->type('input[placeholder="What needs to be done?"]', 'Task To Complete')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500);

            // Помечаем одну задачу как completed
            $browser->within('.todo-item:has-text("Task To Complete")', function ($item) {
                $item->check('.todo-checkbox');
            })
                ->pause(500)
                ->assertSee('2 items left');

            // Проверяем фильтр "All" (должен показывать все задачи)
            $browser->click('button:contains("All")')
                ->pause(200)
                ->assertSee('Active Task 1')
                ->assertSee('Active Task 2')
                ->assertSee('Task To Complete');

            // Проверяем фильтр "Active" (должен показывать только active)
            $browser->click('button:contains("Active")')
                ->pause(200)
                ->assertSee('Active Task 1')
                ->assertSee('Active Task 2')
                ->assertDontSee('Task To Complete');

            // Проверяем фильтр "Completed" (должен показывать только completed)
            $browser->click('button:contains("Completed")')
                ->pause(200)
                ->assertSee('Task To Complete')
                ->assertDontSee('Active Task 1')
                ->assertDontSee('Active Task 2');
        });
    }

    /**
     * Тест обновления счетчика при переключении статуса.
     */
    public function test_counter_updates_on_status_toggle(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем задачу
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Counter Test Task')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->assertSee('1 item left');

            // Переключаем на completed - счетчик должен стать 0
            $browser->check('.todo-checkbox')
                ->pause(500)
                ->assertSee('0 items left');

            // Переключаем обратно - счетчик должен стать 1
            $browser->uncheck('.todo-checkbox')
                ->pause(500)
                ->assertSee('1 item left');
        });
    }
}

