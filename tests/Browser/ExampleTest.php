<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Базовый тест для проверки работы Laravel Dusk.
 */
class ExampleTest extends DuskTestCase
{
    /**
     * Проверка загрузки главной страницы фронтенда.
     */
    public function test_frontend_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:5173')
                ->assertSee('CoolTodo');
        });
    }
}

