<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    public function run(): array;
    public function approve(array $data): bool;
    public function getValidated(array $data, array $excludedKeys = []): array;
    public function getAlerts(array $data, array $excludedKeys = []): array;
    public function set($verifiable): ValidationInterface;
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface;
    public function email(string $verifiable, string $message = 'Email is invalid'): ValidationInterface;
    public function required(string $message = 'You must fill in the field'): ValidationInterface;
    public function integer(string $message = 'Number is required'): ValidationInterface;
    public function min($length, string $message = 'Too few characters specified'): ValidationInterface;
    public function max($length, string $message = 'Too many characters specified'): ValidationInterface;
    public function equals($verifiable, string $message = 'Values ​​do not match'): ValidationInterface;
    public function csrf(array $csrfSession, $message = 'Invalid CSRF token'): ValidationInterface;
}
