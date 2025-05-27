<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru>
 * @license https://mit-license.org/  MIT
 */

namespace Rudra\Validation;

class Validation implements ValidationInterface
{
    private $verifiable;
    private ?string $message = null;
    private bool $checked    = true;

    /**
     * Returns the result of data validation.
     *
     * @return array [validated_value, error_message]
     */
    public function run(): array
    {
        $checked = ($this->checked) ? [$this->verifiable, null] : [false, $this->message];

        $this->message = null;
        $this->checked = true;

        return $checked;
    }

    /**
     * Checks if all elements in the array passed validation.
     *
     * @param array $data Array of validation results
     * @return bool True if all items are valid
     */
    public function approve(array $data): bool
    {
        foreach ($data as $item) {
            if ($item[0] === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets an array of validated values.
     *
     * @param array $data
     * @param array $excludedKeys Keys to exclude from the result
     * @return array Array of validated values
     */
    public function getValidated(array $data, array $excludedKeys = []): array
    {
        $checked = [];

        foreach ($data as $key => $value) {
            $checked[$key] = $value[0];
        }

        return $this->removeExcluded($checked, $excludedKeys);
    }

    /**
     * Gets an array of validation error messages.
     *
     * @param array $data
     * @param array $excludedKeys Keys to exclude from the result
     * @return array Array of error messages
     */
    public function getAlerts(array $data, array $excludedKeys = []): array
    {
        $alerts = [];

        foreach ($data as $key => $value) {
            if (isset($value[1])) {
                $alerts[$key] = $value[1];
            }
        }

        return $this->removeExcluded($alerts, $excludedKeys);
    }

    /**
     * Removes excluded keys from the array.
     *
     * @param array $inputArray Input array
     * @param array $excludedKeys Keys to remove
     * @return array Filtered array
     */
    private function removeExcluded(array $inputArray, array $excludedKeys): array
    {
        foreach ($excludedKeys as $key) {
            unset($inputArray[$key]);
        }

        return $inputArray;
    }

    /**
     * Sets the value to be validated without processing.
     *
     * @param mixed $verifiable Value to validate
     * @return ValidationInterface
     */
    public function set($verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

    /**
     * Sets the value to be validated after sanitizing HTML tags.
     *
     * @param string $verifiable Value to sanitize and validate
     * @param array|string|null $allowableTags Optional allowed HTML tags
     * @return ValidationInterface
     */
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

    /**
     * Validates the email address.
     *
     * @param string $verifiable Email to validate
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function email(string $verifiable, string $message = 'Email is invalid'): ValidationInterface
    {
        $this->set(filter_var($verifiable, FILTER_VALIDATE_EMAIL));

        return $this->validate($this->verifiable ? true : false, $message);
    }

    /**
     * Ensures a value is present (not empty).
     *
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function required(string $message = 'You must fill in the field'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) > 0), $message);
    }

    /**
     * Validates that the value is numeric.
     *
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function integer(string $message = 'Number is required'): ValidationInterface
    {
        return $this->validate(is_numeric($this->verifiable), $message);
    }

    /**
     * Validates that the value has at least the minimum number of characters.
     *
     * @param int $length Minimum length
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function min($length, string $message = 'Too few characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) >= $length), $message);
    }

    /**
     * Validates that the value does not exceed the maximum number of characters.
     *
     * @param int $length Maximum length
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function max($length, string $message = 'Too many characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) <= $length), $message);
    }

    /**
     * Validates that two values are equal.
     *
     * @param mixed $verifiable Value to compare with
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function equals($verifiable, string $message = 'Values ​​do not match'): ValidationInterface
    {
        return $this->validate(($this->verifiable === $verifiable), $message);
    }

    /**
     * Validates against CSRF attacks.
     *
     * @param array $csrfSession List of valid tokens
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    public function csrf(array $csrfSession, $message = 'Invalid CSRF token'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $csrfSession), $message);
    }

    /**
     * Internal validation logic for setting status and error message.
     *
     * @param bool $bool Validation result
     * @param string $message Error message on failure
     * @return ValidationInterface
     */
    private function validate(bool $bool, string $message): ValidationInterface
    {
        if (!$this->checked) return $this;

        $this->checked = $bool;
        $this->message = !$bool ? $message : null;

        return $this;
    }
}
