<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jageard) <jagepard@yandex.ru>
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
    public function email(string $verifiable, string $message = 'Invalid email address'): ValidationInterface;
    public function required(string $message = 'This field is required'): ValidationInterface;
    public function min(int $length, string $message = 'Too few characters'): ValidationInterface;
    public function max(int $length, string $message = 'Too many characters'): ValidationInterface;
    public function equals(mixed $verifiable, string $message = 'Values do not match'): ValidationInterface;
    public function csrf(array $csrfSession, string $message = 'Invalid CSRF token'): ValidationInterface;
    public function url(string $message = 'Invalid URL'): ValidationInterface;
    public function numeric(string $message = 'Numeric value required'): ValidationInterface;
    public function integer(string $message = 'Integer required'): ValidationInterface;
    public function between(int|float $min, int|float $max, string $message = 'Value is out of range'): ValidationInterface;
    public function regex(string $pattern, string $message = 'Invalid format'): ValidationInterface;
    public function date(string $format = 'Y-m-d', string $message = 'Invalid date'): ValidationInterface;
    public function custom(callable $callback, string $message = 'Validation error'): ValidationInterface;
    public function in(array $allowed, string $message = 'Invalid value selected'): ValidationInterface;
}
