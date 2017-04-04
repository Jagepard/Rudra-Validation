<?php

declare(strict_types = 1);

/**
 * Date: 04.04.17
 * Time: 17:13
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;


interface IValidation
{
    /**
     * @return array
     * Собирает результат работы методов класса
     */
    public function run(): array;

    /**
     * @param $data
     *
     * @return Validation
     * Устанавливаем данные без обработки
     */
    public function set($data): Validation;
    /**
     * @param string $data
     * @param null   $allowableTags
     *
     * @return Validation
     * Очищает входящие параметры от ненужных данных
     */
    public function sanitize(string $data, $allowableTags = null): Validation;

    /**
     * @param string|null $salt
     *
     * @return Validation
     */
    public function hash(string $salt = null): Validation;

    /**
     * @param string $message
     *
     * @return Validation
     * Проверяет необходимость заполнения поля - не меннее 1 символа,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function required(string $message = 'Необходимо заполнить поле'): Validation;

    /**
     * @param string $message
     *
     * @return Validation
     * Проверяет являются ли данные числом,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function integer(string $message = 'Необходимо указать число'): Validation;

    /**
     * @param        $data
     * @param string $message
     *
     * @return Validation
     * Проверяет соответствуют ли данные минимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function minLength($data, string $message = 'Указано слишком мало символов'): Validation;

    /**
     * @param        $data
     * @param string $message
     *
     * @return Validation
     * Проверяет соответствуют ли данные максимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function maxLength($data, string $message = 'Указано слишком много символов'): Validation;


    /**
     * @param        $data
     * @param string $message
     *
     * @return Validation
     * Проверяет эквивалентность введенных данных
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function equals($data, string $message = 'Пароли не совпадают'): Validation;

    /**
     * @param        $data
     * @param string $message
     *
     * @return Validation
     * Проверяет email на соответствие
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function email($data, string $message = 'Email указан неверно'): Validation;

    /**
     * @param string $message
     *
     * @return Validation
     * Проверяет верность данных csrf защиты
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function csrf($message = 'csrf'): Validation;

    /**
     * @param        $data
     * @param string $message
     *
     * @return Validation
     * Проверяет верность заполнения капчи
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function captcha($data, $message = 'Пожалуйста заполните поле :: reCaptcha'): Validation;

    /**
     * @param $data
     *
     * @return bool
     * Проверяет все результаты собранные в массив
     */
    public function access($data): bool;

    /**
     * @param       $data
     * @param array $excludedKeys
     *
     * @return mixed
     * Возвращает обработанные и проверенные данные
     * исключая при этом элементы массива $excludedKeys
     */
    public function get(array $data, array $excludedKeys = []);

    /**
     * @param $data
     * @param $excludedKeys
     *
     * @return mixed
     * Возвращает массив ошибок
     * исключая при этом элементы массива $excludedKeys
     */
    public function flash($data, $excludedKeys);
}