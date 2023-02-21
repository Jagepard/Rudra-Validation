<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

interface ValidationInterface
{
    /**
     * Выдает массив с результатом проверки
     * в случае успешной проверки:
     * [$this->verifiable // проверенные данные, null // вместо сообщения об ошибке]
     * в случае если данные не соответствуют требованиям
     * [false // вместо проверенных данных, $this->message // сообщение о несоответствии]
     * ----------------------------------------------------------------------------------
     * Gives an array with the result of the check
     * in case of successful check:
     * [$this->verifiable // verified data, null instead of error message]
     * in case the data does not meet the requirements
     * [false // instead of validated data, $this->message // mismatch message]
     *
     * @return array
     */
    public function run(): array;

        /**
     * Checks if all elements of an array are validated
     * Array example:
     * $processed = [
     *      'csrf_field' => Validation::sanitize($inputData["csrf_field"])->csrf(Session::get("csrf_token"))->run(),
     *      'search'     => Validation::sanitize($inputData["search"])->min(1)->max(50)->run(),
     *      'redirect'   => Validation::sanitize($inputData["redirect"])->max(500)->run(),
     * ];
     * -------------------------------------------------------------------------------------------------------------
     * Проверяет все ли элементы массива прошли проверку
     *
     * @param  array   $data
     * @return boolean
     */
    public function approve(array $data): bool;

    /**
     * Get an array of validated data
     * $excludedKeys allows you to exclude elements which are not required after verification
     * example: Validation::getValidated($processed, ["csrf_field", "_method"]);
     * --------------------------------------------------------------------------------------
     * Получить массив данных прошедших проверку
     * $excludedKeys позволяет исключить элементы, которые не требуются после проверки
     *
     * @param  array $data
     * @param  array $excludedKeys
     * @return array
     */
    public function getValidated(array $data, array $excludedKeys = []): array;

    /**
     * Receives messages about non-compliance with validation requirements
     * $excludedKeys allows you to exclude elements which are not required after verification
     * example: Validation::getAlerts($processed, ["_method"]);
     * --------------------------------------------------------------------------------------
     * Получает сообщения о несоответствии требованиям валидации
     * $excludedKeys позволяет исключить элементы, которые не требуются после проверки
     *
     * @param  array $data
     * @param  array $excludedKeys
     * @return array
     */
    public function getAlerts(array $data, array $excludedKeys = []): array;

    /**
     * Sets the data to be checked without processing
     * ----------------------------------------------
     * Устанавливает проверяемые данные без обработки
     *
     * @param  [type]              $verifiable
     * @return ValidationInterface
     */
    public function set($data): ValidationInterface;

    /**
     * Sets the data to be checked with processing for strings 
     * with valid tags: $allowableTags
     * ---------------------------------------------------
     * Устанавливает проверяемые данные с обработкой для строк 
     * с указанием допустимых тегов: $allowableTags
     *
     * @param  string                 $verifiable
     * @param  array|string|null|null $allowableTags
     * @return ValidationInterface
     */
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface;

    /**
     * Sets the data before checking that the value is a valid e-mail.
     * Sets the status to false and an error message if validation fails
     * -------------------------------------------------------------------------------------
     * Устанавливает данные предварительно проверяя, что значение является корректным e-mail
     * Устанавливает статус false и сообщение об ошибке, если проверка не пройдена
     * 
     * @param  string              $verifiable
     * @param  string              $message
     * @return ValidationInterface
     */
    public function email(string $data, string $message = 'Email is invalid'): ValidationInterface;

    /**
     * Checks if a string value is set in $this->verifiable
     * ---------------------------------------------------------------
     * Проверяет установлено ли строковое значение в $this->verifiable
     *
     * @param  string              $message
     * @return ValidationInterface
     */
    public function required(string $message = 'You must fill in the field'): ValidationInterface;

    /**
     * Finds whether a $this->verifiable is a number or a numeric string 
     * -----------------------------------------------------------------------------
     * Проверяет, является ли $this->verifiable числом или строкой, содержащей число  
     *
     * @param  string              $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Number is required'): ValidationInterface;

    /**
     * Checks the string value in $this->verifiable against the minimum allowed number of characters
     * ---------------------------------------------------------------------------------------------
     * Проверяет строковое значение в $this->verifiable на минимально допустимое количество символов
     *
     * @param  [type]              $length
     * @param  string              $message
     * @return ValidationInterface
     */
    public function min($length, string $message = 'Too few characters specified'): ValidationInterface;

    /**
     * Checks the string value in $this->verifiable against the maximum allowed number of characters
     * ----------------------------------------------------------------------------------------------
     * Проверяет строковое значение в $this->verifiable на максимально допустимое количество символов
     *
     * @param  [type]              $length
     * @param  string              $message
     * @return ValidationInterface
     */
    public function max($length, string $message = 'Too many characters specified'): ValidationInterface;

    /**
     * Compares the equivalence of $verifiable and $this->verifiable values
     * --------------------------------------------------------------------
     * Сравнивает эквивалентность значений $verifiable и $this->verifiable
     *
     * @param  [type]              $verifiable
     * @param  string              $message
     * @return ValidationInterface
     */
    public function equals($data, string $message = 'Values do not match'): ValidationInterface;

    /**
     * Cross-Site Request Forgery Protection
     * --------------------------------------
     * Защита от межсайтовой подделки запроса
     *
     * @param  array               $csrfSession
     * @param  string              $message
     * @return ValidationInterface
     */
    public function csrf(array $csrfSession, $message = 'csrf'): ValidationInterface;
}
