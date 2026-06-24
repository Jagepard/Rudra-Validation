<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Validation;

class Validation implements ValidationInterface
{
    private mixed   $verifiable;
    private bool    $checked = true;
    private ?string $message = null;
    private array   $aliases = [];

    /**
     * Returns the result of the check as an array.
     * The first element is the validated value (or false), the second is the error message (or null).
     * Resets the internal state for the next validation chain.
     */
    #[\Override]
    public function run(): array
    {
        $result = [
            $this->checked ? $this->verifiable : false,
            $this->message
        ];

        // Reset state
        $this->checked = true;
        $this->message = null;

        return $result;
    }

    /**
     * Checks an array of results for errors.
     * Returns true if all elements are successful (the first value in each subarray === true).
     */
    #[\Override]
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
     * Extracts validated values from the data array.
     * Returns an associative array where keys are field names and values are the validated data.
     * Excludes the specified keys if they are passed.
     */
    #[\Override]
    public function getValidated(array $data, array $excludedKeys = []): array
    {
        $validated = [];

        foreach ($data as $key => $value) {
            $validated[$key] = $value[0]; // value or false
        }

        return $this->removeExcluded($validated, $excludedKeys);
    }

    /**
     * Sets aliases for fields used in validation.
     * Aliases are applied in the getErrors method to form human-readable field names in error messages.
     */
    #[\Override]
    public function setAliases(array $aliases): void
    {
        $this->aliases = $aliases;
    }

    /**
     * Extracts error messages from validation data.
     * Returns an associative array where the key is the field name,
     * and the value is an array containing the error message and the field alias.
     */
    #[\Override]
    public function getErrors(array $data, array $excludedKeys = []): array
    {
        $errors = [];

        foreach ($data as $key => $value) {
            if ($value[1] !== null) {
                $errors[$key] = [
                    'msg'   => $value[1],
                    'alias' => $this->aliases[$key] ?? $key
                ];
            }
        }

        return $this->removeExcluded($errors, $excludedKeys);
    }

    /**
     * Removes the specified keys from the array and returns the cleaned array.
     */
    private function removeExcluded(array $inputArray, array $excludedKeys): array
    {
        foreach ($excludedKeys as $key) {
            unset($inputArray[$key]);
        }

        return $inputArray;
    }

    /**
     * Sets the value to be checked (validated).
     */
    #[\Override]
    public function set(mixed $verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

    /**
     * Cleans the input string from HTML tags (with the option to allow certain tags)
     * and saves the result for further checking.
     */
    #[\Override]
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

    /**
     * Checks if the specified string is a valid email address.
     * Saves the result of the check and sets an error message if the email is invalid.
     */
    #[\Override]
    public function email(string $verifiable, string $message = 'Email указан неверно'): ValidationInterface
    {
        $this->set($verifiable);
        return $this->validate(filter_var($verifiable, FILTER_VALIDATE_EMAIL) !== false, $message);
    }

    /**
     * Checks if the field is filled (not an empty string).
     * If the value is missing or consists of spaces — sets the specified error message.
     */
    #[\Override]
    public function required(string $message = 'Поле должно быть заполнено'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) > 0), $message);
    }

    /**
     * Checks that the string length is not less than the specified value.
     * Sets an error message if the check fails.
     */
    #[\Override]
    public function min(int $length, string $message = 'Слишком мало символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) >= $length), $message);
    }

    /**
     * Checks that the string length does not exceed the specified value.
     * Sets an error message if the check fails.
     */
    #[\Override]
    public function max(int $length, string $message = 'Слишком много символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) <= $length), $message);
    }

    /**
     * Checks if the current value matches the specified one.
     * Uses strict comparison.
     */
    #[\Override]
    public function equals(mixed $verifiable, string $message = 'Значение не совпадает'): ValidationInterface
    {
        return $this->validate(($this->verifiable === $verifiable), $message);
    }

    /**
     * Checks if the current value is contained in the array of valid CSRF tokens.
     * Used for protection against cross-site request forgery (CSRF).
     */
    #[\Override]
    public function csrf(array $csrfSession, string $message = 'Invalid CSRF token'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $csrfSession, true), $message);
    }

    /**
     * Checks if the current value is a valid URL.
     * Sets the specified error message if the check fails.
     */
    #[\Override]
    public function url(string $message = 'Некорректный URL-адрес'): ValidationInterface
    {
        $isValid = filter_var($this->verifiable, FILTER_VALIDATE_URL) !== false;
        return $this->validate($isValid, $message);
    }

    /**
     * Checks if the current value is numeric (integer or floating-point number).
     * Sets the specified error message if the check fails.
     */
    #[\Override]
    public function numeric(string $message = 'Требуется числовое значение'): ValidationInterface
    {
        return $this->validate(is_numeric($this->verifiable), $message);
    }

    /**
     * Checks if the current value is a valid integer (not a float or string representation of a float).
     * Sets the specified error message if the check fails.
     */
    #[\Override]
    public function integer(string $message = 'Укажите целое число'): ValidationInterface
    {
        return $this->validate(
            is_numeric($this->verifiable) && (filter_var($this->verifiable, FILTER_VALIDATE_INT) !== false),
            $message
        );
    }

    /**
     * Checks that the numeric value is within the specified range (inclusive).
     * Sets the specified error message if the value is outside the range or not numeric.
     */
    #[\Override]
    public function between(int|float $min, int|float $max, string $message = 'Значение выходит за пределы диапазона'): ValidationInterface
    {
        if (!is_numeric($this->verifiable)) {
            return $this->validate(false, $message);
        }
        $val = (float)$this->verifiable;
        return $this->validate($val >= $min && $val <= $max, $message);
    }

    /**
     * Checks if the current value matches the specified regular expression pattern.
     * Sets the specified error message if the pattern does not match.
     */
    #[\Override]
    public function regex(string $pattern, string $message = 'Неверный формат'): ValidationInterface
    {
        return $this->validate(preg_match($pattern, $this->verifiable) === 1, $message);
    }

    /**
     * Checks if the current value is a valid date in the specified format.
     * Uses strict comparison to avoid ambiguous date interpretations.
     * Sets the specified error message if the date is invalid.
     */
    #[\Override]
    public function date(string $format = 'Y-m-d', string $message = 'Дата указана неверно'): ValidationInterface
    {
        $d = \DateTime::createFromFormat($format, $this->verifiable);
        return $this->validate($d && $d->format($format) === $this->verifiable, $message);
    }

    /**
     * Performs a custom validation using a user-defined callback function.
     * The callback receives the current value and must return true or false.
     * Sets the specified error message if the callback returns false.
     */
    #[\Override]
    public function custom(callable $callback, string $message = 'Ошибка валидации'): ValidationInterface
    {
        return $this->validate($callback($this->verifiable), $message);
    }

    /**
     * Checks if the current value is in the allowed list.
     */
    #[\Override]
    public function in(array $allowed, string $message = 'Выбрано неверное значение'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $allowed, true), $message);
    }

    /**
     * Performs a condition check and saves the validation result.
     * If the check fails, sets an error message.
     */
    private function validate(bool $condition, string $message): ValidationInterface
    {
        if ($this->checked) {
            $this->checked = $condition;
            $this->message = $condition ? null : $message;
        }

        return $this;
    }
}
