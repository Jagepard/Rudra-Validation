<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

class Validation implements ValidationInterface
{
    /**
     * Checked data
     * ------------------
     * Проверяемые данные
     *
     * @var [type]
     */
    private $verifiable;

    /**
     * Reporting non-compliance
     * ------------------------------------
     * Сообщение о несоответсвии требований
     *
     * @var string|null
     */
    private ?string $message = null;

    /**
     * Check status
     * ---------------
     * Статус проверки
     *
     * @var boolean
     */
    private bool $checked = true;

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
    public function run(): array
    {
        $checked = ($this->checked) ? [$this->verifiable, null] : [false, $this->message];

        $this->message = null;
        $this->checked = true;

        return $checked;
    }

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
    public function getValidated(array $data, array $excludedKeys = []): array
    {
        $checked = [];

        foreach ($data as $key => $value) {
            $checked[$key] = $value[0];
        }

        return $this->removeExcluded($checked, $excludedKeys);
    }

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
     * Removes $excludedKeys from the array
     * --------------------------------------------------
     * Удаляет $excludedKeys исключенные ключи из массива
     *
     * @param  array $inputArray
     * @param  array $excludedKeys
     * @return void
     */
    private function removeExcluded(array $inputArray, array $excludedKeys)
    {
        foreach ($excludedKeys as $excludedKey) {
            if (isset($inputArray[$excludedKey])) {
                unset($inputArray[$excludedKey]);
            }
        }

        return $inputArray ?? [];
    }

    /**
     * Sets the data to be checked without processing
     * ----------------------------------------------
     * Устанавливает проверяемые данные без обработки
     *
     * @param  [type]              $verifiable
     * @return ValidationInterface
     */
    public function set($verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

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
    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

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
    public function email(string $verifiable, string $message = 'Email is invalid'): ValidationInterface
    {
        $this->set(filter_var($verifiable, FILTER_VALIDATE_EMAIL));

        return $this->validate($this->verifiable ? true : false, $message);
    }


    /**
     * Checks if a string value is set in $this->verifiable
     * ---------------------------------------------------------------
     * Проверяет установлено ли строковое значение в $this->verifiable
     *
     * @param  string              $message
     * @return ValidationInterface
     */
    public function required(string $message = 'You must fill in the field'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) > 0), $message);
    }

    /**
     * Finds whether a $this->verifiable is a number or a numeric string 
     * -----------------------------------------------------------------------------
     * Проверяет, является ли $this->verifiable числом или строкой, содержащей число  
     *
     * @param  string              $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Number is required'): ValidationInterface
    {
        return $this->validate(is_numeric($this->verifiable), $message);
    }

    /**
     * Checks the string value in $this->verifiable against the minimum allowed number of characters
     * ---------------------------------------------------------------------------------------------
     * Проверяет строковое значение в $this->verifiable на минимально допустимое количество символов
     *
     * @param  [type]              $length
     * @param  string              $message
     * @return ValidationInterface
     */
    public function min($length, string $message = 'Too few characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) >= $length), $message);
    }

    /**
     * Checks the string value in $this->verifiable against the maximum allowed number of characters
     * ----------------------------------------------------------------------------------------------
     * Проверяет строковое значение в $this->verifiable на максимально допустимое количество символов
     *
     * @param  [type]              $length
     * @param  string              $message
     * @return ValidationInterface
     */
    public function max($length, string $message = 'Too many characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) <= $length), $message);
    }

    /**
     * Compares the equivalence of $verifiable and $this->verifiable values
     * --------------------------------------------------------------------
     * Сравнивает эквивалентность значений $verifiable и $this->verifiable
     *
     * @param  [type]              $verifiable
     * @param  string              $message
     * @return ValidationInterface
     */
    public function equals($verifiable, string $message = 'Values ​​do not match'): ValidationInterface
    {
        return $this->validate(($this->verifiable === $verifiable), $message);
    }

    /**
     * Cross-Site Request Forgery Protection
     * --------------------------------------
     * Защита от межсайтовой подделки запроса
     *
     * @param  array               $csrfSession
     * @param  string              $message
     * @return ValidationInterface
     */
    public function csrf(array $csrfSession, $message = 'csrf'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $csrfSession), $message);
    }


    /**
     * Set status and error message if validation fails
     * ---------------------------------------------------------------------
     * Устанавливает статус и сообщение об ошибке, если проверка не пройдена
     *
     * @param  boolean             $bool
     * @param  string              $message
     * @return ValidationInterface
     */
    private function validate(bool $bool, string $message): ValidationInterface
    {
        /*
        | If one of the previous checks in the chain did not pass further, we do not check
        | --------------------------------------------------------------------------------
        | Если одна из предыдущих проверок в цепочке не прошла, дальше не проверяем
        */
        if (!$this->checked) {
            return $this;
        }

        if (!$bool) {
            $this->checked = $bool;
            $this->message = $message;
        }

        return $this;
    }
}
