<?php

declare(strict_types = 1);

/**
 * Date: 09.04.17
 * Time: 17:50
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;


/**
 * Class ValidationOutputTrait
 *
 * @package Rudra
 */
trait ValidationOutputTrait
{

    /**
     * @param $data
     *
     * @return bool
     * Проверяет все результаты собранные в массив
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
     * @param       $data
     * @param array $excludedKeys
     *
     * @return mixed
     * Возвращает обработанные и проверенные данные
     * исключая при этом элементы массива $excludedKeys
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
     * @param $data
     * @param $excludedKeys
     *
     * @return mixed
     * Возвращает массив ошибок
     * исключая при этом элементы массива $excludedKeys
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
