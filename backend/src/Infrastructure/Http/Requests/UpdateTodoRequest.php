<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request для обновления задачи.
 */
class UpdateTodoRequest extends FormRequest
{
    /**
     * Определяет, может ли пользователь выполнить этот запрос.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для запроса.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:1'],
        ];
    }
}

