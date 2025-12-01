<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E тест для удаления задач через UI.
 */
class DeleteTodoTest extends DuskTestCase
{
    /**
     * Тест удаления одной задачи через кнопку Delete.
     */
    public function test_user_can_delete_single_todo(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем задачу для удаления
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Task To Delete')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->assertSee('Task To Delete')
                ->assertSee('1 item left');

            // Удаляем задачу (кнопка появляется при hover)
            $browser->mouseover('.todo-item')
                ->pause(200) // Даем время на появление кнопки
                ->click('.todo-delete')
                ->pause(200) // Даем время на появление диалога
                ->acceptDialog() // Подтверждаем удаление
                ->pause(500) // Даем время на удаление
                ->assertDontSee('Task To Delete')
                ->assertSee('No todos yet. Add a new one!');
        });
    }

    /**
     * Тест удаления всех completed задач через "Clear completed".
     */
    public function test_user_can_clear_completed_todos(): void
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
                ->type('input[placeholder="What needs to be done?"]', 'Completed Task 1')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->type('input[placeholder="What needs to be done?"]', 'Completed Task 2')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500);

            // Помечаем две задачи как completed
            $browser->within('.todo-item:has-text("Completed Task 1")', function ($item) {
                $item->check('.todo-checkbox');
            })
                ->pause(500)
                ->within('.todo-item:has-text("Completed Task 2")', function ($item) {
                    $item->check('.todo-checkbox');
                })
                ->pause(500)
                ->assertSee('2 items left');

            // Удаляем все completed задачи
            $browser->click('button:contains("Clear completed")')
                ->pause(200) // Даем время на появление диалога
                ->acceptDialog() // Подтверждаем удаление
                ->pause(500) // Даем время на удаление
                ->assertSee('Active Task 1')
                ->assertSee('Active Task 2')
                ->assertDontSee('Completed Task 1')
                ->assertDontSee('Completed Task 2')
                ->assertSee('2 items left')
                ->assertMissing('button:contains("Clear completed")'); // Кнопка должна исчезнуть
        });
    }

    /**
     * Тест обновления счетчика при удалении.
     */
    public function test_counter_updates_on_delete(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем несколько задач
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Task 1')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->type('input[placeholder="What needs to be done?"]', 'Task 2')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->assertSee('2 items left');

            // Удаляем одну задачу
            $browser->mouseover('.todo-item:first-of-type')
                ->pause(200)
                ->click('.todo-delete:first-of-type')
                ->pause(200)
                ->acceptDialog()
                ->pause(500)
                ->assertSee('1 item left');
        });
    }
}

