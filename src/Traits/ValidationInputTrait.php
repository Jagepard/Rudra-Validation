<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra\Traits;

use Rudra\Interfaces\ValidationInterface;

/**
 * Trait ValidationInputTrait
 * @package Rudra\Traits
 */
trait ValidationInputTrait
{

    /**
     * Для данных проходящих валидацию
     *
     * @var string
     */
    protected $data;

    /**
     * Устанавливаем данные без обработки
     *
     * @param $data
     * @return ValidationInterface
     */
    public function set($data): ValidationInterface
    {
        $this->setData($data);
        return $this;
    }


    /**
     * Очищает входящие параметры от ненужных данных
     *
     * @param string $data
     * @param null   $allowableTags
     * @return ValidationInterface
     */
    public function sanitize(string $data, $allowableTags = null): ValidationInterface
    {
        $this->setData(strip_tags(trim($data), $allowableTags));
        return $this;
    }

    /**
     * @param string|null $salt
     * @return ValidationInterface
     */
    public function hash(string $salt = null): ValidationInterface
    {
        $this->setData(substr(crypt($this->data, '$6$rounds=' . $salt), 10));
        return $this;
    }

    /**
     * @return string
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}
