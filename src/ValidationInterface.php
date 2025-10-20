<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    /**
     * Returns the result of the check as an array.
     * The first element is a flag indicating the success of the check, the second is an error message (or null).
     * Resets the internal state: clears the message and marks the check as completed.
     * --------------------
     * Возвращает результат проверки в виде массива.
     * Первый элемент — флаг успешности проверки, второй — сообщение об ошибке (или null).
     * Сбрасывает внутреннее состояние: очищает сообщение и помечает проверку как выполненную.
     */
    public function run(): array;

    /**
     * Checks an array of results for errors.
     * Returns true if all elements are successful (the first value in each subarray === true).
     * --------------------
     * Проверяет массив результатов на наличие ошибок.
     * Возвращает true, если все элементы успешны (первое значение в каждом подмассиве === true).
     */
    public function approve(array $data): bool;

    /**
     * Extracts the results of the check (true/false) from the data array and returns them in a clean form.
     * Excludes the specified keys if they are passed.
     * --------------------
     * Извлекает результаты проверки (true/false) из массива данных и возвращает их в чистом виде.
     * Исключает указанные ключи, если они переданы.
     */
    public function getValidated(array $data, array $excludedKeys = []): array;


    /**
     * Extracts messages (such as errors or warnings) from the check data.
     * Returns an associative array: field keys => corresponding messages.
     * Excludes the specified keys if they are passed.
     * --------------------
     * Извлекает сообщения (например, ошибки или предупреждения) из данных проверки.
     * Возвращает ассоциативный массив: ключ поле => соответствующeе сообщениe.
     * Исключает указанные ключи, если они переданы.
     */
    public function getErrors(array $data, array $excludedKeys = []): array;

    /**
     * Sets the value to be checked (validated).
     * --------------------
     * Устанавливает значение, которое будет проверяться (валидироваться).
     */
    public function set(mixed $verifiable): ValidationInterface;

    /**
     * Cleans the input string from HTML tags (with the option to allow certain tags)
     * and saves the result for further checking.
     * --------------------
     * Очищает входную строку от HTML-тегов (с возможностью разрешить определённые теги)
     * и сохраняет результат для дальнейшей проверки.
     */
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface;

    /**
     * Checks if the specified string is a valid email address.
     * Saves the result of the check and sets an error message if the email is invalid.
     * --------------------
     * Проверяет, является ли указанная строка корректным email-адресом.
     * Сохраняет результат проверки и устанавливает сообщение об ошибке, если email некорректен.
     */
    public function email(string $verifiable, string $message = 'Email указан неверно'): ValidationInterface;

    /**
     * Checks if the field is filled (not an empty string).
     * If the value is missing or consists of spaces — sets the specified error message.
     * --------------------
     * Проверяет, заполнено ли поле (не пустая строка).
     * Если значение отсутствует или состоит из пробелов — устанавливает указанное сообщение об ошибке.
     */
    public function required(string $message = 'Поле должно быть заполнено'): ValidationInterface;

    /**
     * Checks that the string length is not less than the specified value.
     * Sets an error message if the check fails.
     * --------------------
     * Проверяет, что длина строки не меньше указанного значения.
     * Устанавливает сообщение об ошибке, если проверка не пройдена.
     */
    public function min(int $length, string $message = 'Слишком мало символов'): ValidationInterface;

    /**
     * Checks that the string length does not exceed the specified value.
     * Sets an error message if the check fails.
     * --------------------
     * Проверяет, что длина строки не превышает указанного значения.
     * Устанавливает сообщение об ошибке, если проверка не пройдена.
     */
    public function max(int $length, string $message = 'Слишком много символов'): ValidationInterface;

    /**
     * Checks if the current value matches the specified one.
     * Uses strict comparison.
     * -----------------------------
     * Проверяет, совпадает ли текущее значение с указанным.
     * Использует строгое сравнение.
     */
    public function equals(mixed $verifiable, string $message = 'Значение не совпадает'): ValidationInterface;
    
    /**
     * Checks if the current value is contained in the array of valid CSRF tokens.
     * Used for protection against cross-site request forgery (CSRF).
     * --------------------
     * Проверяет, содержится ли текущее значение в массиве допустимых CSRF-токенов.
     * Используется для защиты от межсайтовой подделки запросов (CSRF).
     */
    public function csrf(array $csrfSession, string $message = 'Invalid CSRF token'): ValidationInterface;

    /**
     * Checks if the current value is a valid URL.
     * Sets the specified error message if the check fails.
     * --------------------
     * Проверяет, является ли текущее значение корректным URL-адресом.
     * Устанавливает указанное сообщение об ошибке, если проверка не пройдена.
     */
    public function url(string $message = 'Некорректный URL-адрес'): ValidationInterface;

    /**
     * Checks if the current value is numeric (integer or floating-point number).
     * Sets the specified error message if the check fails.
     * --------------------
     * Проверяет, является ли текущее значение числовым (целым или числом с плавающей точкой).
     * Устанавливает указанное сообщение об ошибке, если проверка не пройдена.
     */
    public function numeric(string $message = 'Требуется числовое значение'): ValidationInterface;

    /**
     * Checks if the current value is a valid integer (not a float or string representation of a float).
     * Sets the specified error message if the check fails.
     * --------------------
     * Проверяет, является ли текущее значение корректным целым числом (а не дробным или строкой с плавающей точкой).
     * Устанавливает указанное сообщение об ошибке, если проверка не пройдена.
     */
    public function integer(string $message = 'Укажите целое число'): ValidationInterface;

    /**
     * Checks that the numeric value is within the specified range (inclusive).
     * Sets the specified error message if the value is outside the range or not numeric.
     * --------------------
     * Проверяет, что числовое значение находится в пределах заданного диапазона (включительно).
     * Устанавливает указанное сообщение об ошибке, если значение выходит за пределы диапазона или не является числом.
     */
    public function between(int|float $min, int|float $max, string $message = 'Значение выходит за пределы диапазона'): ValidationInterface;

    /**
     * Checks if the current value matches the specified regular expression pattern.
     * Sets the specified error message if the pattern does not match.
     * --------------------
     * Проверяет, соответствует ли текущее значение заданному регулярному выражению.
     * Устанавливает указанное сообщение об ошибке, если шаблон не совпадает.
     */
    public function regex(string $pattern, string $message = 'Неверный формат'): ValidationInterface;

    /**
     * Checks if the current value is a valid date in the specified format.
     * Uses strict comparison to avoid ambiguous date interpretations.
     * Sets the specified error message if the date is invalid.
     * --------------------
     * Проверяет, является ли текущее значение корректной датой в указанном формате.
     * Использует строгое сравнение, чтобы избежать неоднозначной интерпретации дат.
     * Устанавливает указанное сообщение об ошибке, если дата некорректна.
     */
    public function date(string $format = 'Y-m-d', string $message = 'Дата указана неверно'): ValidationInterface;

    /**
     * Performs a custom validation using a user-defined callback function.
     * The callback receives the current value and must return true or false.
     * Sets the specified error message if the callback returns false.
     * --------------------
     * Выполняет кастомную валидацию с использованием пользовательской функции-колбэка.
     * Колбэк получает текущее значение и должен вернуть true или false.
     * Устанавливает указанное сообщение об ошибке, если колбэк возвращает false.
     */
    public function custom(callable $callback, string $message = 'Ошибка валидации'): ValidationInterface;

    /**
     * Checks if the current value is in the allowed list.
     * --------------------
     * Проверяет, содержится ли текущее значение в разрешённом списке.
     */
    public function in(array $allowed, string $message = 'Выбрано неверное значение'): ValidationInterface;
}
