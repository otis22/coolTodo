<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E тест для редактирования задачи через UI.
 */
class EditTodoTest extends DuskTestCase
{
    /**
     * Тест редактирования задачи через double-click.
     */
    public function test_user_can_edit_todo(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем задачу для редактирования
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Original Task Title')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500);

            // Редактируем задачу через double-click
            $browser->doubleClick('.todo-title')
                ->pause(200) // Даем время на активацию режима редактирования
                ->type('.todo-edit', 'Updated Task Title')
                ->keys('.todo-edit', '{enter}')
                ->pause(500) // Даем время на сохранение
                ->assertSee('Updated Task Title')
                ->assertDontSee('Original Task Title');
        });
    }

    /**
     * Тест отмены редактирования через Escape.
     */
    public function test_user_can_cancel_edit_with_escape(): void
    {
        $this->browse(function (Browser $browser) {
            // Создаем задачу
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'Task To Cancel Edit')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500);

            // Начинаем редактирование и отменяем
            $browser->doubleClick('.todo-title')
                ->pause(200)
                ->type('.todo-edit', 'This Should Not Be Saved')
                ->keys('.todo-edit', '{escape}')
                ->pause(200)
                ->assertSee('Task To Cancel Edit')
                ->assertDontSee('This Should Not Be Saved');
        });
    }
}

