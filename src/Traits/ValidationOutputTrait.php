<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra\Traits;

/**
 * Trait ValidationOutputTrait
 * @package Rudra\Traits
 */
trait ValidationOutputTrait
{

    /**
     * Проверяет все результаты собранные в массив
     *
     * @param $data
     * @return bool
     */
    public function access($data): bool
    {
        foreach ($data as $item) {
            if ($item[0] === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Возвращает обработанные и проверенные данные
     * исключая при этом элементы массива $excludedKeys
     *
     * @param       $data
     * @param array $excludedKeys
     * @return mixed
     */
    public function get(array $data, array $excludedKeys = [])
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = $value[0];
        }

        foreach ($excludedKeys as $excludedKey) {
            if (isset($result[$excludedKey])) {
                unset($result[$excludedKey]);
            }
        }

        return isset($result) ? $result : [];
    }

    /**
     * Возвращает массив ошибок
     * исключая при этом элементы массива $excludedKeys
     *
     * @param $data
     * @param $excludedKeys
     * @return mixed
     */
    public function flash($data, $excludedKeys)
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (isset($value[1])) {
                $result[$key] = $value[1];
            }
        }

        foreach ($excludedKeys as $excludedKey) {
            if (isset($result[$excludedKey])) {
                unset($result[$excludedKey]);
            }
        }

        return isset($result) ? $result : [];
    }
}
