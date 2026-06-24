<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    public function run(): array;
    public function approve(array $data): bool;
    public function getValidated(array $data, array $excludedKeys = []): array;
    public function getErrors(array $data, array $excludedKeys = []): array;
    public function setAliases(array $aliases): void;
    public function set(mixed $verifiable): ValidationInterface;
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface;
    public function email(string $verifiable, string $message = 'Email is invalid'): ValidationInterface;
    public function required(string $message = 'Field is required'): ValidationInterface;
    public function min(int $length, string $message = 'Слишком мало символов'): ValidationInterface;
    public function max(int $length, string $message = 'Слишком много символов'): ValidationInterface;
    public function equals(mixed $verifiable, string $message = 'Значение не совпадает'): ValidationInterface;
    public function csrf(array $csrfSession, string $message = 'Invalid CSRF token'): ValidationInterface;
    public function url(string $message = 'Некорректный URL-адрес'): ValidationInterface;
    public function numeric(string $message = 'Требуется числовое значение'): ValidationInterface;
    public function integer(string $message = 'Укажите целое число'): ValidationInterface;
    public function between(int|float $min, int|float $max, string $message = 'Значение выходит за пределы диапазона'): ValidationInterface;
    public function regex(string $pattern, string $message = 'Неверный формат'): ValidationInterface;
    public function date(string $format = 'Y-m-d', string $message = 'Дата указана неверно'): ValidationInterface;
    public function custom(callable $callback, string $message = 'Ошибка валидации'): ValidationInterface;
    public function in(array $allowed, string $message = 'Выбрано неверное значение'): ValidationInterface;
}
