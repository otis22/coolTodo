<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * E2E тест для создания задачи через UI.
 */
class CreateTodoTest extends DuskTestCase
{
    /**
     * Тест создания задачи через форму ввода.
     */
    public function test_user_can_create_todo(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'E2E Test Task')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500) // Даем время на выполнение API запроса
                ->assertSee('E2E Test Task')
                ->assertSee('1 item left');
        });
    }

    /**
     * Тест создания нескольких задач.
     */
    public function test_user_can_create_multiple_todos(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo')
                ->type('input[placeholder="What needs to be done?"]', 'First Task')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->type('input[placeholder="What needs to be done?"]', 'Second Task')
                ->keys('input[placeholder="What needs to be done?"]', '{enter}')
                ->pause(500)
                ->assertSee('First Task')
                ->assertSee('Second Task')
                ->assertSee('2 items left');
        });
    }
}

