<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

use Rudra\Traits\ValidationInputTrait;
use Rudra\Traits\ValidationOutputTrait;
use Rudra\Interfaces\ContainerInterface;
use Rudra\Interfaces\ValidationInterface;

/**
 * Class Validation
 * @package Rudra
 */
class Validation implements ValidationInterface
{

    use ValidationInputTrait;
    use ValidationOutputTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var string
     */
    protected $captchaSecret;
    /**
     * @var null
     */
    protected $message = null;
    /**
     * @var bool
     */
    protected $result = true;

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
        $result        = ($this->result) ? [$this->data, null] : [false, $this->message];
        $this->message = null;
        $this->result  = true;
        return $result;
    }

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function required(string $message = 'Необходимо заполнить поле'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->data) > 0), $message);
    }

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function integer(string $message = 'Необходимо указать число'): ValidationInterface
    {
        return $this->validate(is_numeric($this->data), $message);
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function minLength($data, string $message = 'Указано слишком мало символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->data) >= $data), $message);
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function maxLength($data, string $message = 'Указано слишком много символов'): ValidationInterface
    {
        return $this->validate((mb_strlen($this->data) <= $data), $message);
    }


    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function equals($data, string $message = 'Пароли не совпадают'): ValidationInterface
    {
        return $this->validate(($this->data == $data), $message);
    }

    /**
     * @param        $data
     * @param string $message
     * @return ValidationInterface
     */
    public function email($data, string $message = 'Email указан неверно'): ValidationInterface
    {
        $this->data = filter_var($data, FILTER_VALIDATE_EMAIL);
        return $this->validate($this->data ? true : false, $message);
    }

    /**
     * @param string $message
     * @return ValidationInterface
     */
    public function csrf($message = 'csrf'): ValidationInterface
    {
        if (!in_array($this->data, $this->container->getSession('csrf_token'))) {
            $this->data    = $this->container->getSession('csrf_token', '0');
            $this->result  = false;
            $this->message = $message;
        } else {
            $_POST['csrf'] = $this->data;
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
            $this->result  = false;
            $this->message = $message;

            return $this;
        }

        $remoteAddress = $this->container->getServer('REMOTE_ADDR') ?? '127.0.0.1';
        $response      = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->captchaSecret . "&response=" . $captcha . "&remoteip=" . $remoteAddress), true);

        if ($this->captchaSecret == 'test_success') {
            $response['success'] = true;
        }

        if ($response['success'] === false) {
            $this->result  = false;
            $this->message = $message;
        } else {
            $this->data = $response['success'];
        }

        return $this;
    }

    /**
     * @param bool   $bool
     * @param string $message
     * @return $this
     */
    protected function validate(bool $bool, string $message): ValidationInterface
    {
        if (!$this->result) return $this;
        if (!$this->result = $bool) $this->message = $message;
        return $this;
    }
}
