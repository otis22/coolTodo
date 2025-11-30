<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent модель для таблицы todos.
 */
class Todo extends Model
{
    protected $table = 'todos';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}

