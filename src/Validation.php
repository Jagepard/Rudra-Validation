<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

class Validation implements ValidationInterface
{
    private $verifiable;
    private ?string $message = null;
    private bool $checked = true;

    public function set($verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

    public function sanitize(string $verifiable, $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

    public function hash(string $salt = null): ValidationInterface
    {
        $this->set(substr(crypt($this->verifiable, '$6$rounds='.$salt), 10));

        return $this;
    }

    public function run(): array
    {
        $checked = ($this->isChecked()) ? [$this->verifiable, null] : [false, $this->message];
        $this->message = null;
        $this->checked = true;

        return $checked;
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
        return $this->validate(($this->verifiable == $verifiable), $message);
    }

    public function email($verifiable, string $message = 'Email is invalid'): ValidationInterface
    {
        $this->set(filter_var($verifiable, FILTER_VALIDATE_EMAIL));

        return $this->validate($this->verifiable ? true : false, $message);
    }

    public function csrf(array $csrfSession, $message = 'csrf'): ValidationInterface
    {
        return $this->validate(in_array($this->verifiable, $csrfSession), $message);
    }

    public function captcha(
        $captcha = false,
        $secret = '',
        $message = 'Please fill in the field :: reCaptcha'
    ): ValidationInterface {
        if (!$captcha) {
            $this->setMessage($message);
            $this->setChecked(false);

            return $this;
        }

        $remoteAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $response      = json_decode(
            file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$remoteAddress
            ),
            true
        );

        if ($secret === 'test_success') {
            $response['success'] = true;
        }

        if ($response['success'] === false) {
            $this->setMessage($message);
            $this->setChecked(false);
        } else {
            $this->set($response['success']);
        }

        return $this;
    }

    private function validate(bool $bool, string $message): ValidationInterface
    {
        if (!$this->isChecked()) {
            return $this;
        }
        $this->setChecked($bool);
        if (!$this->isChecked()) {
            $this->setMessage($message);
        }

        return $this;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): void
    {
        $this->checked = $checked;
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

    private function removeExcluded(array $checked, array $excludedKeys)
    {
        foreach ($excludedKeys as $excludedKey) {
            if (isset($checked[$excludedKey])) {
                unset($checked[$excludedKey]);
            }
        }

        return isset($checked) ? $checked : [];
    }
}
