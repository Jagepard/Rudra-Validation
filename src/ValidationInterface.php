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


interface ValidationInterface
{
    /**
     * @return array
     * Собирает результат работы методов класса
     */
    public function run(): array;

    /**
     * @param $data
     *
     * @return ValidationInterface
     * Устанавливаем данные без обработки
     */
    public function set($data): ValidationInterface;
    /**
     * @param string $data
     * @param null   $allowableTags
     *
     * @return ValidationInterface
     * Очищает входящие параметры от ненужных данных
     */
    public function sanitize(string $data, $allowableTags = null): ValidationInterface;

    /**
     * @param string|null $salt
     *
     * @return ValidationInterface
     */
    public function hash(string $salt = null): ValidationInterface;

    /**
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет необходимость заполнения поля - не меннее 1 символа,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function required(string $message = 'Необходимо заполнить поле'): ValidationInterface;

    /**
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет являются ли данные числом,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function integer(string $message = 'Необходимо указать число'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет соответствуют ли данные минимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function minLength($data, string $message = 'Указано слишком мало символов'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет соответствуют ли данные максимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function maxLength($data, string $message = 'Указано слишком много символов'): ValidationInterface;


    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет эквивалентность введенных данных
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function equals($data, string $message = 'Пароли не совпадают'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет email на соответствие
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function email($data, string $message = 'Email указан неверно'): ValidationInterface;

    /**
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет верность данных csrf защиты
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function csrf($message = 'csrf'): ValidationInterface;

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет верность заполнения капчи
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function captcha($data, $message = 'Пожалуйста заполните поле :: reCaptcha'): ValidationInterface;

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