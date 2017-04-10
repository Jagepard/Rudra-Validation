<?php

declare(strict_types = 1);

/**
 * Date: 09.04.17
 * Time: 17:48
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

/**
 * Class ValidationInputTrait
 *
 * @package Rudra
 */
trait ValidationInputTrait
{

    /**
     * @var string
     * Для данных проходящих валидацию
     */
    protected $data;

    /**
     * @param $data
     *
     * @return ValidationInterface
     * Устанавливаем данные без обработки
     */
    public function set($data): ValidationInterface
    {
        $this->setData($data);

        return $this;
    }


    /**
     * @param string $data
     * @param null   $allowableTags
     *
     * @return ValidationInterface
     * Очищает входящие параметры от ненужных данных
     */
    public function sanitize(string $data, $allowableTags = null): ValidationInterface
    {
        $this->setData(strip_tags(trim($data), $allowableTags));

        return $this;
    }

    /**
     * @param string|null $salt
     *
     * @return ValidationInterface
     */
    public function hash(string $salt = null): ValidationInterface
    {
        $this->setData(substr(crypt($this->data(), '$6$rounds=' . $salt), 10));

        return $this;
    }

    /**
     * @return string
     */
    protected function data()
    {
        return $this->data;
    }

    /**
     * @param $data
     */
    protected function setData($data)
    {
        $this->data = $data;
    }
}
