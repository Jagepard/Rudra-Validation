<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra;

use Rudra\Traits\ValidationInputTrait;
use Rudra\Traits\ValidationOutputTrait;
use Rudra\Interfaces\ContainerInterface;
use Rudra\Interfaces\ValidationInterface;

class Validation implements ValidationInterface
{
    use ValidationInputTrait;
    use ValidationOutputTrait;

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var string
     */
    private $captchaSecret;
    /**
     * @var null
     */
    private $message = null;
    /**
     * @var bool
     */
    private $result = true;

    /**
     * Validation constructor.
     * @param ContainerInterface $container
     * @param null               $captchaSecret
     */
    public function __construct(ContainerInterface $container, $captchaSecret = null)
    {
        $this->container     = $container;
        $this->captchaSecret = $captchaSecret;
    }

    /**
     * @return array
     */
    public function run(): array
    {
        $result = ($this->isResult()) ? [$this->data(), null] : [false, $this->message];

        $this->setMessage(null);
        $this->setResult(true);

        return $result;
    }

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function required(string $message = 'Необходимо заполнить поле'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->data()) > 0), $message);
    }

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Необходимо указать число'): ValidationInterface
    {
        return $this->validate(is_numeric($this->data()), $message);
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function minLength($data, string $message = 'Указано слишком мало символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->data()) >= $data), $message);
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function maxLength($data, string $message = 'Указано слишком много символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->data()) <= $data), $message);
    }


    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function equals($data, string $message = 'Пароли не совпадают'): ValidationInterface
    {
        return $this->validate(($this->data() == $data), $message);
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function email($data, string $message = 'Email указан неверно'): ValidationInterface
    {
        $this->setData(filter_var($data, FILTER_VALIDATE_EMAIL));
        return $this->validate($this->data() ? true : false, $message);
    }

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function csrf($message = 'csrf'): ValidationInterface
    {
        if (!in_array($this->data(), $this->container()->getSession('csrf_token'))) {
            $this->setData($this->container()->getSession('csrf_token', '0'));
            $this->setMessage($message);
            $this->setResult(false);
        } else {
            $_POST['csrf'] = $this->data();
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function captcha($data, $message = 'Пожалуйста заполните поле :: reCaptcha'): ValidationInterface
    {
        $captcha = $data ?? false;

        if (!$captcha) {
            $this->setMessage($message);
            $this->setResult(false);

            return $this;
        }

        $remoteAddress = $this->container()->getServer('REMOTE_ADDR') ?? '127.0.0.1';
        $response      = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->captchaSecret() . "&response=" . $captcha . "&remoteip=" . $remoteAddress), true);

        if ($this->captchaSecret() == 'test_success') {
            $response['success'] = true;
        }

        if ($response['success'] === false) {
            $this->setMessage($message);
            $this->setResult(false);
        } else {
            $this->setData($response['success']);
        }

        return $this;
    }

    /**
     * @param bool   $bool
     * @param string $message
     * @return $this
     */
    private function validate(bool $bool, string $message): ValidationInterface
    {
        if (!$this->isResult()) return $this;
        $this->setResult($bool);
        if (!$this->isResult()) $this->setMessage($message);
        return $this;
    }

    /**
     * @return string
     */
    public function captchaSecret(): string
    {
        return $this->captchaSecret;
    }

    /**
     * @param string $captchaSecret
     */
    public function setCaptchaSecret(string $captchaSecret): void
    {
        $this->captchaSecret = $captchaSecret;
    }

    /**
     * @return null
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @param null $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isResult(): bool
    {
        return $this->result;
    }

    /**
     * @param bool $result
     */
    public function setResult(bool $result): void
    {
        $this->result = $result;
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }
}
