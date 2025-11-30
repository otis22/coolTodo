<?php

declare(strict_types=1);

namespace App\Domain\Models;

/**
 * Value Object для статуса задачи.
 *
 * Неизменяемый объект-значение, представляющий статус задачи (active или completed).
 */
final class TaskStatus
{
    private const ACTIVE = 'active';
    private const COMPLETED = 'completed';

    private function __construct(
        private readonly string $value
    ) {
        if ($value !== self::ACTIVE && $value !== self::COMPLETED) {
            throw new \InvalidArgumentException("Invalid task status: {$value}");
        }
    }

    /**
     * Создает статус "active".
     */
    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    /**
     * Создает статус "completed".
     */
    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    /**
     * Создает статус из строки.
     *
     * @param string $value Значение статуса ('active' или 'completed')
     * @return self
     * @throws \InvalidArgumentException Если значение невалидно
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * Возвращает строковое значение статуса.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Проверяет, является ли статус "active".
     */
    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    /**
     * Проверяет, является ли статус "completed".
     */
    public function isCompleted(): bool
    {
        return $this->value === self::COMPLETED;
    }

    /**
     * Переключает статус (active ↔ completed).
     */
    public function toggle(): self
    {
        return $this->isActive() ? self::completed() : self::active();
    }

    /**
     * Сравнивает два статуса на равенство.
     */
    public function equals(TaskStatus $other): bool
    {
        return $this->value === $other->value;
    }
}

