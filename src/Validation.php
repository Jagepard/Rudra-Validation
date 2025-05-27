<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru>
 * @license https://mit-license.org/  MIT
 */

namespace Rudra\Validation;

class Validation implements ValidationInterface
{
    private $verifiable;
    private ?string $message = null;
    private bool $checked    = true;

    public function run(): array
    {
        $checked = ($this->checked) ? [$this->verifiable, null] : [false, $this->message];

        $this->message = null;
        $this->checked = true;

        return $checked;
    }

    public function approve(array $data): bool
    {
        foreach ($data as $item) {
            if ($item[0] === false) {
                return false;
            }
        }

        return true;
    }

    public function getValidated(array $data, array $excludedKeys = []): array
    {
        $checked = [];

        foreach ($data as $key => $value) {
            $checked[$key] = $value[0];
        }

        return $this->removeExcluded($checked, $excludedKeys);
    }

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

    private function removeExcluded(array $inputArray, array $excludedKeys): array
    {
        foreach ($excludedKeys as $key) {
            unset($inputArray[$key]);
        }

        return $inputArray;
    }

    public function set($verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

    public function sanitize(string $verifiable, array|string|null $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

    public function email(string $verifiable, string $message = 'Email is invalid'): ValidationInterface
    {
        $this->set(filter_var($verifiable, FILTER_VALIDATE_EMAIL));

        return $this->validate($this->verifiable ? true : false, $message);
    }

    public function required(string $message = 'You must fill in the field'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) > 0), $message);
    }

    public function integer(string $message = 'Number is required'): ValidationInterface
    {
        return $this->validate(is_numeric($this->verifiable), $message);
    }

    public function min($length, string $message = 'Too few characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) >= $length), $message);
    }

    public function max($length, string $message = 'Too many characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) <= $length), $message);
    }

    public function equals($verifiable, string $message = 'Values ​​do not match'): ValidationInterface
    {
        return $this->validate(($this->verifiable === $verifiable), $message);
    }

    public function csrf(array $csrfSession, $message = 'Invalid CSRF token'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $csrfSession), $message);
    }

    private function validate(bool $bool, string $message): ValidationInterface
    {
        if (!$this->checked) return $this;

        $this->checked = $bool;
        $this->message = !$bool ? $message : null;

        return $this;
    }
}
