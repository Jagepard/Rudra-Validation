<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    /**
     * @return array
     */
    public function run(): array;

    /**
     * @param $data
     * @return ValidationInterface
     */
    public function set($data): ValidationInterface;

    /**
     * @param string $data
     * @param null   $allowableTags
     * @return ValidationInterface
     */
    public function sanitize(string $data, $allowableTags = null): ValidationInterface;

    /**
     * @param string|null $salt
     * @return ValidationInterface
     */
    public function hash(string $salt = null): ValidationInterface;

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function required(string $message = 'You must fill in the field'): ValidationInterface;

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Number is required'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function minLength($data, string $message = 'Too few characters specified'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function maxLength($data, string $message = 'Too many characters specified'): ValidationInterface;


    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function equals($data, string $message = 'Values ​​do not match'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function email($data, string $message = 'Email is invalid'): ValidationInterface;

    /**
     * @param  array  $csrfSession
     * @param  string  $message
     * @return ValidationInterface
     */
    public function csrf(array $csrfSession, $message = 'csrf'): ValidationInterface;

    /**
     * @param  bool  $captcha
     * @param  string  $secret
     * @param  string  $message
     * @return ValidationInterface
     */
    public function captcha($captcha = false, $secret = '', $message = 'Please fill in the field :: reCaptcha'): ValidationInterface;

    /**
     * @param $data
     * @return bool
     */
    public function checkArray(array $data): bool;

    /**
     * @param  array  $data
     * @param  array  $excludedKeys
     * @return array
     */
    public function getChecked(array $data, array $excludedKeys = []): array;

    /**
     * @param $data
     * @param $excludedKeys
     * @return mixed
     */
    public function getAlerts($data, $excludedKeys): array;
}
