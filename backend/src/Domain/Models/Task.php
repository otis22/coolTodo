<?php

declare(strict_types=1);

namespace App\Domain\Models;

/**
 * Domain Model для задачи (Todo).
 *
 * @property int $id
 * @property string $title
 * @property string $status
 */
class Task
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';

    public function __construct(
        private ?int $id,
        private string $title,
        private string $status = self::STATUS_ACTIVE
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function updateTitle(string $title): void
    {
        $this->title = $title;
    }

    public function toggleStatus(): void
    {
        $this->status = $this->status === self::STATUS_ACTIVE
            ? self::STATUS_COMPLETED
            : self::STATUS_ACTIVE;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}

