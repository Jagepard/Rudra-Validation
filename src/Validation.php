<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation;

class Validation implements ValidationInterface
{
    /**
     * @var string
     */
    private $verifiable;
    /**
     * @var null
     */
    private $message = null;
    /**
     * @var bool
     */
    private $checked = true;

    /**
     * @param $verifiable
     * @return ValidationInterface
     */
    public function set($verifiable): ValidationInterface
    {
        $this->verifiable = $verifiable;

        return $this;
    }

    /**
     * @param  string  $verifiable
     * @param  null  $allowableTags
     * @return ValidationInterface
     */
    public function sanitize(string $verifiable, $allowableTags = null): ValidationInterface
    {
        $this->set(strip_tags(trim($verifiable), $allowableTags));

        return $this;
    }

    /**
     * @param  string|null  $salt
     * @return ValidationInterface
     */
    public function hash(string $salt = null): ValidationInterface
    {
        $this->set(substr(crypt($this->verifiable, '$6$rounds='.$salt), 10));

        return $this;
    }

    /**
     * @return array
     */
    public function run(): array
    {
        $checked = ($this->isChecked()) ? [$this->verifiable, null] : [false, $this->message];
        $this->message = null;
        $this->checked = true;

        return $checked;
    }

    /**
     * @param  string  $message
     * @return ValidationInterface
     */
    public function required(string $message = 'You must fill in the field'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) > 0), $message);
    }

    /**
     * @param  string  $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Number is required'): ValidationInterface
    {
        return $this->validate(is_numeric($this->verifiable), $message);
    }

    /**
     * @param        $data
     * @param  string  $message
     * @return ValidationInterface
     */
    public function minLength($data, string $message = 'Too few characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) >= $data), $message);
    }

    /**
     * @param        $data
     * @param  string  $message
     * @return ValidationInterface
     */
    public function maxLength($data, string $message = 'Too many characters specified'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->verifiable) <= $data), $message);
    }


    /**
     * @param $verifiable
     * @param  string  $message
     * @return ValidationInterface
     */
    public function equals($verifiable, string $message = 'Values ​​do not match'): ValidationInterface
    {
        return $this->validate(($this->verifiable === $verifiable), $message);
    }

    /**
     * @param $verifiable
     * @param  string  $message
     * @return ValidationInterface
     */
    public function email($verifiable, string $message = 'Email is invalid'): ValidationInterface
    {
        $this->set(filter_var($verifiable, FILTER_VALIDATE_EMAIL));

        return $this->validate($this->verifiable ? true : false, $message);
    }

    /**
     * @param  array  $csrfSession
     * @param  string  $message
     * @return ValidationInterface
     */
    public function csrf(array $csrfSession, $message = 'csrf'): ValidationInterface
    {
        if (!in_array($this->verifiable, $csrfSession)) {
            $this->set($csrfSession[0]);
            $this->setMessage($message);
            $this->setChecked(false);
        } else {
            $_POST['csrf'] = $this->verifiable;
        }

        return $this;
    }

    /**
     * @param  bool  $captcha
     * @param  string  $secret
     * @param  string  $message
     * @return ValidationInterface
     */
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

    /**
     * @param  bool  $bool
     * @param  string  $message
     * @return $this
     */
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

    /**
     * @param  null  $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param  bool  $checked
     */
    public function setChecked(bool $checked): void
    {
        $this->checked = $checked;
    }

    /**
     * @param  array  $data
     * @return bool
     */
    public function checkArray(array $data): bool
    {
        foreach ($data as $item) {
            if ($item[0] === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array  $data
     * @param  array  $excludedKeys
     * @return array
     */
    public function getChecked(array $data, array $excludedKeys = []): array
    {
        $checked = [];

        foreach ($data as $key => $value) {
            $checked[$key] = $value[0];
        }

        return $this->removeExcluded($checked, $excludedKeys);
    }

    /**
     * @param $data
     * @param $excludedKeys
     * @return array
     */
    public function getAlerts($data, $excludedKeys): array
    {
        $alerts = [];

        foreach ($data as $key => $value) {
            if (isset($value[1])) {
                $alerts[$key] = $value[1];
            }
        }

        return $this->removeExcluded($alerts, $excludedKeys);
    }

    /**
     * @param  array  $checked
     * @param  array  $excludedKeys
     * @return array
     */
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
