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
    private bool $checked = true;
    private ?string $message = null;
    private array $aliases = [];

    /**
     * Returns the result of the check as an array.
     * The first element is a flag indicating the success of the check, the second is an error message (or null).
     * Resets the internal state: clears the message and marks the check as completed.
     * --------------------
     * Возвращает результат проверки в виде массива.
     * Первый элемент — флаг успешности проверки, второй — сообщение об ошибке (или null).
     * Сбрасывает внутреннее состояние: очищает сообщение и помечает проверку как выполненную.
     */
    public function run(): array
    {
        $checked = ($this->checked) ? [$this->verifiable, null] : [false, $this->message];

        $this->message = null;
        $this->checked = true;

        return $checked;
    }

    /**
     * Checks an array of results for errors.
     * Returns true if all elements are successful (the first value in each subarray === true).
     * --------------------
     * Проверяет массив результатов на наличие ошибок.
     * Возвращает true, если все элементы успешны (первое значение в каждом подмассиве === true).
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
     * Extracts the results of the check (true/false) from the data array and returns them in a clean form.
     * Excludes the specified keys if they are passed.
     * --------------------
     * Извлекает результаты проверки (true/false) из массива данных и возвращает их в чистом виде.
     * Исключает указанные ключи, если они переданы.
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
     * Устанавливает алиасы для полей, используемых в валидации.
     * Алиасы применяются в методе getErrors для формирования человекочитаемых имён полей в сообщениях об ошибках.
     * --------------------
     * Sets aliases for fields used in validation.
     * Aliases are applied in the getErrors method to form human-readable field names in error messages.
     *
     * @param array
     * @return void
     */
    public function setAliases(array $aliases): void
    {
        $this->aliases = $aliases;
    }

    /**
     * Extracts error messages from validation data.
     * Returns an associative array where the key is the field name,
     * and the value is an array containing the error message and the field alias.
     * --------------------
     * Извлекает сообщения об ошибках из данных валидации.
     * Возвращает ассоциативный массив, где ключ - это имя поля,
     * а значение - массив с сообщением об ошибке и алиасом поля.
     *
     * @param  array $data
     * @param  array $excludedKeys
     * @return array
     */
    public function getErrors(array $data, array $excludedKeys = []): array
    {
        $errors = [];
        foreach ($data as $key => $value) {
            if (isset($value[1])) {
                $alias = $this->aliases[$key] ?? $key;
                // Возвращаем ассоциативный массив
                $errors[$key] = [
                    'msg'   => $value[1], // Оригинальное сообщение
                    'alias' => $alias     // Алиас поля
                ];
            }
        }
        return $this->removeExcluded($errors, $excludedKeys);
    }

    /**
     * Removes the specified keys from the array and returns the cleaned array.
     * --------------------
     * Удаляет указанные ключи из массива и возвращает очищенный массив.
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
     * --------------------
     * Устанавливает значение, которое будет проверяться (валидироваться).
     */
    public function set(mixed $verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

    /**
     * Cleans the input string from HTML tags (with the option to allow certain tags)
     * and saves the result for further checking.
     * --------------------
     * Очищает входную строку от HTML-тегов (с возможностью разрешить определённые теги)
     * и сохраняет результат для дальнейшей проверки.
     */
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

    /**
     * Checks if the specified string is a valid email address.
     * Saves the result of the check and sets an error message if the email is invalid.
     * --------------------
     * Проверяет, является ли указанная строка корректным email-адресом.
     * Сохраняет результат проверки и устанавливает сообщение об ошибке, если email некорректен.
     */
    public function email(string $verifiable, string $message = 'Email указан неверно'): ValidationInterface
    {
        $this->set(filter_var($verifiable, FILTER_VALIDATE_EMAIL));

        return $this->validate($this->verifiable ? true : false, $message);
    }

    /**
     * Checks if the field is filled (not an empty string).
     * If the value is missing or consists of spaces — sets the specified error message.
     * --------------------
     * Проверяет, заполнено ли поле (не пустая строка).
     * Если значение отсутствует или состоит из пробелов — устанавливает указанное сообщение об ошибке.
     */
    public function required(string $message = 'Поле должно быть заполнено'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) > 0), $message);
    }

    /**
     * Checks that the string length is not less than the specified value.
     * Sets an error message if the check fails.
     * --------------------
     * Проверяет, что длина строки не меньше указанного значения.
     * Устанавливает сообщение об ошибке, если проверка не пройдена.
     */
    public function min(int $length, string $message = 'Слишком мало символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) >= $length), $message);
    }

    /**
     * Checks that the string length does not exceed the specified value.
     * Sets an error message if the check fails.
     * --------------------
     * Проверяет, что длина строки не превышает указанного значения.
     * Устанавливает сообщение об ошибке, если проверка не пройдена.
     */
    public function max(int $length, string $message = 'Слишком много символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) <= $length), $message);
    }

    /**
     * Checks if the current value matches the specified one.
     * Uses strict comparison.
     * --------------------
     * Проверяет, совпадает ли текущее значение с указанным.
     * Использует строгое сравнение.
     */
    public function equals(mixed $verifiable, string $message = 'Значение не совпадает'): ValidationInterface
    {
        return $this->validate(($this->verifiable === $verifiable), $message);
    }

    /**
     * Checks if the current value is contained in the array of valid CSRF tokens.
     * Used for protection against cross-site request forgery (CSRF).
     * --------------------
     * Проверяет, содержится ли текущее значение в массиве допустимых CSRF-токенов.
     * Используется для защиты от межсайтовой подделки запросов (CSRF).
     */
    public function csrf(array $csrfSession, string $message = 'Invalid CSRF token'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $csrfSession), $message);
    }

    /**
     * Checks if the current value is a valid URL.
     * Sets the specified error message if the check fails.
     * --------------------
     * Проверяет, является ли текущее значение корректным URL-адресом.
     * Устанавливает указанное сообщение об ошибке, если проверка не пройдена.
     */
    public function url(string $message = 'Некорректный URL-адрес'): ValidationInterface
    {
        $isValid = filter_var($this->verifiable, FILTER_VALIDATE_URL) !== false;
        return $this->validate($isValid, $message);
    }

    /**
     * Checks if the current value is numeric (integer or floating-point number).
     * Sets the specified error message if the check fails.
     * --------------------
     * Проверяет, является ли текущее значение числовым (целым или числом с плавающей точкой).
     * Устанавливает указанное сообщение об ошибке, если проверка не пройдена.
     */
    public function numeric(string $message = 'Требуется числовое значение'): ValidationInterface
    {
        return $this->validate(is_numeric($this->verifiable), $message);
    }

    /**
     * Checks if the current value is a valid integer (not a float or string representation of a float).
     * Sets the specified error message if the check fails.
     * --------------------
     * Проверяет, является ли текущее значение корректным целым числом (а не дробным или строкой с плавающей точкой).
     * Устанавливает указанное сообщение об ошибке, если проверка не пройдена.
     */
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
     * --------------------
     * Проверяет, что числовое значение находится в пределах заданного диапазона (включительно).
     * Устанавливает указанное сообщение об ошибке, если значение выходит за пределы диапазона или не является числом.
     */
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
     * --------------------
     * Проверяет, соответствует ли текущее значение заданному регулярному выражению.
     * Устанавливает указанное сообщение об ошибке, если шаблон не совпадает.
     */
    public function regex(string $pattern, string $message = 'Неверный формат'): ValidationInterface
    {
        return $this->validate(preg_match($pattern, $this->verifiable) === 1, $message);
    }

    /**
     * Checks if the current value is a valid date in the specified format.
     * Uses strict comparison to avoid ambiguous date interpretations.
     * Sets the specified error message if the date is invalid.
     * --------------------
     * Проверяет, является ли текущее значение корректной датой в указанном формате.
     * Использует строгое сравнение, чтобы избежать неоднозначной интерпретации дат.
     * Устанавливает указанное сообщение об ошибке, если дата некорректна.
     */
    public function date(string $format = 'Y-m-d', string $message = 'Дата указана неверно'): ValidationInterface
    {
        $d = \DateTime::createFromFormat($format, $this->verifiable);
        return $this->validate($d && $d->format($format) === $this->verifiable, $message);
    }

    /**
     * Performs a custom validation using a user-defined callback function.
     * The callback receives the current value and must return true or false.
     * Sets the specified error message if the callback returns false.
     * --------------------
     * Выполняет кастомную валидацию с использованием пользовательской функции-колбэка.
     * Колбэк получает текущее значение и должен вернуть true или false.
     * Устанавливает указанное сообщение об ошибке, если колбэк возвращает false.
     */
    public function custom(callable $callback, string $message = 'Ошибка валидации'): ValidationInterface
    {
        return $this->validate($callback($this->verifiable), $message);
    }

    /**
     * Checks if the current value is in the allowed list.
     * --------------------
     * Проверяет, содержится ли текущее значение в разрешённом списке.
     */
    public function in(array $allowed, string $message = 'Выбрано неверное значение'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $allowed, true), $message);
    }

    /**
     * Performs a condition check and saves the validation result.
     * If the check fails, sets an error message.
     * --------------------
     * Выполняет проверку условия и сохраняет результат валидации.
     * Если проверка не пройдена, устанавливает сообщение об ошибке.
     */
    private function validate(bool $bool, string $message): ValidationInterface
    {
        if (!$this->checked) return $this;

        $this->checked = $bool;
        $this->message = !$bool ? $message : null;

        return $this;
    }
}
