<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra\Interfaces;

/**
 * Interface ValidationInterface
 * @package Rudra
 */
interface ValidationInterface
{

    /**
     * Собирает результат работы методов класса
     *
     * @return array
     */
    public function run(): array;

    /**
     * Устанавливаем данные без обработки
     *
     * @param $data
     * @return ValidationInterface
     */
    public function set($data): ValidationInterface;

    /**
     * Очищает входящие параметры от ненужных данных
     *
     * @param string $data
     * @param null   $allowableTags
     * @return ValidationInterface
     */
    public function sanitize(string $data, $allowableTags = null): ValidationInterface;

    /**
     * Проверяет необходимость заполнения поля - не меннее 1 символа,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param string|null $salt
     * @return ValidationInterface
     */
    public function hash(string $salt = null): ValidationInterface;

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function required(string $message = 'Необходимо заполнить поле'): ValidationInterface;

    /**
     * Проверяет являются ли данные числом,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param string $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Необходимо указать число'): ValidationInterface;

    /**
     * Проверяет соответствуют ли данные минимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function minLength($data, string $message = 'Указано слишком мало символов'): ValidationInterface;

    /**
     * Проверяет соответствуют ли данные максимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function maxLength($data, string $message = 'Указано слишком много символов'): ValidationInterface;


    /**
     * Проверяет эквивалентность введенных данных
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function equals($data, string $message = 'Пароли не совпадают'): ValidationInterface;

    /**
     * Проверяет email на соответствие
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function email($data, string $message = 'Email указан неверно'): ValidationInterface;

    /**
     * Проверяет верность данных csrf защиты
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param string $message
     * @return ValidationInterface
     */
    public function csrf($message = 'csrf'): ValidationInterface;

    /**
     * Проверяет верность заполнения капчи
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     *
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function captcha($data, $message = 'Пожалуйста заполните поле :: reCaptcha'): ValidationInterface;

    /**
     * Проверяет все результаты собранные в массив
     *
     * @param $data
     * @return bool
     */
    public function access(array $data): bool;

    /**
     * Возвращает обработанные и проверенные данные
     * исключая при этом элементы массива $excludedKeys
     *
     * @param       $data
     * @param array $excludedKeys
     * @return mixed
     */
    public function get(array $data, array $excludedKeys = []): array;

    /**
     * Возвращает массив ошибок
     * исключая при этом элементы массива $excludedKeys
     *
     * @param $data
     * @param $excludedKeys
     * @return mixed
     */
    public function flash($data, $excludedKeys): array;
}
