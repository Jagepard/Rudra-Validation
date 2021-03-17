<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    public function run(): array;
    public function set($data): ValidationInterface;
    public function sanitize(string $data, $allowableTags = null): ValidationInterface;
    public function hash(string $salt = null): ValidationInterface;
    public function required(string $message = 'You must fill in the field'): ValidationInterface;
    public function integer(string $message = 'Number is required'): ValidationInterface;
    public function min($length, string $message = 'Too few characters specified'): ValidationInterface;
    public function max($length, string $message = 'Too many characters specified'): ValidationInterface;
    public function equals($data, string $message = 'Values do not match'): ValidationInterface;
    public function email($data, string $message = 'Email is invalid'): ValidationInterface;
    public function csrf(array $csrfSession, $message = 'csrf'): ValidationInterface;
    public function captcha($captcha = false, $secret = '', $message = 'Please fill in the field :: reCaptcha'): ValidationInterface;
    public function approve(array $data): bool;
    public function getValidated(array $data, array $excludedKeys = []): array;
    public function getAlerts(array $data, array $excludedKeys = []): array;
}
