<?php

declare(strict_types=1);

namespace App\Domain\Models;

/**
 * Domain Model для задачи (Todo).
 *
 * @property int $id
 * @property string $title
 * @property TaskStatus $status
 */
class Task
{
    /**
     * @deprecated Используйте TaskStatus::active() вместо этого
     */
    public const STATUS_ACTIVE = 'active';

    /**
     * @deprecated Используйте TaskStatus::completed() вместо этого
     */
    public const STATUS_COMPLETED = 'completed';

    private TaskStatus $status;

    public function __construct(
        private ?int $id,
        private string $title,
        ?TaskStatus $status = null
    ) {
        $this->status = $status ?? TaskStatus::active();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Возвращает статус задачи как TaskStatus Value Object.
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * Возвращает строковое значение статуса (для обратной совместимости).
     *
     * @deprecated Используйте getStatus()->getValue() вместо этого
     */
    public function getStatusValue(): string
    {
        return $this->status->getValue();
    }

    public function updateTitle(string $title): void
    {
        $this->title = $title;
    }

    public function toggleStatus(): void
    {
        $this->status = $this->status->toggle();
    }

    public function isCompleted(): bool
    {
        return $this->status->isCompleted();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}
