<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    /**
     * Returns the result of data validation.
     *
     * @return array [validated_value, error_message]
     */
    public function run(): array;

    /**
     * Checks if all elements in the array passed validation.
     *
     * @param array $data Array of validation results
     * @return bool True if all items are valid
     */
    public function approve(array $data): bool;

    /**
     * Gets an array of validated values.
     *
     * @param array $data
     * @param array $excludedKeys Keys to exclude from the result
     * @return array Array of validated values
     */
    public function getValidated(array $data, array $excludedKeys = []): array;

    /**
     * Gets an array of validation error messages.
     *
     * @param array $data
     * @param array $excludedKeys Keys to exclude from the result
     * @return array Array of error messages
     */
    public function getAlerts(array $data, array $excludedKeys = []): array;

    /**
     * Sets the value to be validated without processing.
     *
     * @param mixed $verifiable Value to validate
     * @return ValidationInterface
     */
    public function set($verifiable): ValidationInterface;

    /**
     * Sets the value to be validated after sanitizing HTML tags.
     *
     * @param string $verifiable Value to sanitize and validate
     * @param array|string|null $allowableTags Optional allowed HTML tags
     * @return ValidationInterface
     */
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface;

        /**
     * Validates the email address.
     *
     * @param string $verifiable Email to validate
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function email(string $verifiable, string $message = 'Email is invalid'): ValidationInterface;

    /**
     * Ensures a value is present (not empty).
     *
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function required(string $message = 'You must fill in the field'): ValidationInterface;

    /**
     * Validates that the value is numeric.
     *
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function integer(string $message = 'Number is required'): ValidationInterface;

    /**
     * Validates that the value has at least the minimum number of characters.
     *
     * @param int $length Minimum length
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function min($length, string $message = 'Too few characters specified'): ValidationInterface;

    /**
     * Validates that the value does not exceed the maximum number of characters.
     *
     * @param int $length Maximum length
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function max($length, string $message = 'Too many characters specified'): ValidationInterface;

    /**
     * Validates that two values are equal.
     *
     * @param mixed $verifiable Value to compare with
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function equals($verifiable, string $message = 'Values ​​do not match'): ValidationInterface;

    /**
     * Validates against CSRF attacks.
     *
     * @param array $csrfSession List of valid tokens
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function csrf(array $csrfSession, $message = 'Invalid CSRF token'): ValidationInterface;
}
