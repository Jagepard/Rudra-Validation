<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Traits;

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

        return $this->getResult($result, $excludedKeys);
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

        return $this->getResult($result, $excludedKeys);
    }

    /**
     * @param array $result
     * @param array $excludedKeys
     * @return array
     */
    private function getResult(array $result, array $excludedKeys)
    {
        foreach ($excludedKeys as $excludedKey) {
            if (isset($result[$excludedKey])) {
                unset($result[$excludedKey]);
            }
        }

        return isset($result) ? $result : [];
    }
}
