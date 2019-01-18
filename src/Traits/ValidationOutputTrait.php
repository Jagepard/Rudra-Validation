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
     * @param array $data
     * @return bool
     */
    public function access(array $data): bool
    {
        foreach ($data as $item) {
            if ($item[0] === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @param array $excludedKeys
     * @return array
     */
    public function get(array $data, array $excludedKeys = []): array
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
     * @return array
     */
    public function flash($data, $excludedKeys): array
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
