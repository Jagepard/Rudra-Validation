<?php

declare(strict_types = 1);

/**
 * Date: 03.02.16
 * Time: 18:13
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

/**
 * Class Validation
 *
 * @package Rudra
 *          Класс валидации данных
 */
class Validation implements ValidationInterface
{

    use ValidationInputTrait;
    use ValidationOutputTrait;
    use SetContainerTrait;

    /**
     * @var string
     */
    protected $captchaSecret;

    /**
     * @var null
     * Для сообщения об ошибке если данные
     * не прошли проверку
     */
    protected $message = null;

    /**
     * @var bool
     * Результат проверки
     */
    protected $result = true;

    /**
     * Validation constructor.
     *
     * @param ContainerInterface $container
     * @param null       $captchaSecret
     */
    public function __construct(ContainerInterface $container, $captchaSecret = null)
    {
        $this->container     = $container;
        $this->captchaSecret = $captchaSecret;
    }

    /**
     * @return array
     * Собирает результат работы методов класса
     */
    public function run(): array
    {
        if ($this->isResult()) {
            return [$this->data(), null];
        }

        $result = [false, $this->message()];

        $this->setMessage(null);
        $this->setResult(true);

        return $result;
    }

    /**
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет необходимость заполнения поля - не меннее 1 символа,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function required(string $message = 'Необходимо заполнить поле'): ValidationInterface
    {
        if (!$this->isResult()) {
            return $this;
        }

        $this->setResult((mb_strlen($this->data) > 0) ? true : false);

        if (!$this->isResult()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет являются ли данные числом,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function integer(string $message = 'Необходимо указать число'): ValidationInterface
    {
        if (!$this->isResult()) {
            return $this;
        }

        $this->setResult((is_numeric($this->data())) ? true : false);

        if (!$this->isResult()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет соответствуют ли данные минимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function minLength($data, string $message = 'Указано слишком мало символов'): ValidationInterface
    {
        if (!$this->isResult()) {
            return $this;
        }

        $this->setResult((mb_strlen($this->data()) >= $data) ? true : false);

        if (!$this->isResult()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет соответствуют ли данные максимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function maxLength($data, string $message = 'Указано слишком много символов'): ValidationInterface
    {
        if (!$this->isResult()) {
            return $this;
        }

        $this->setResult((mb_strlen($this->data()) <= $data) ? true : false);

        if (!$this->isResult()) {
            $this->setMessage($message);
        }

        return $this;
    }


    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет эквивалентность введенных данных
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function equals($data, string $message = 'Пароли не совпадают'): ValidationInterface
    {
        if (!$this->isResult()) {
            return $this;
        }

        $this->setResult(($this->data() == $data) ? true : false);

        if (!$this->isResult()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет email на соответствие
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function email($data, string $message = 'Email указан неверно'): ValidationInterface
    {
        $this->set(filter_var($data, FILTER_VALIDATE_EMAIL));

        if (!$this->data()) {
            $this->setResult(false);
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет верность данных csrf защиты
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function csrf($message = 'csrf'): ValidationInterface
    {
        if (!in_array($this->data(), $this->container()->getSession('csrf_token'))) {
            $this->setData($this->container()->getSession('csrf_token', '0'));
            $this->setResult(false);
            $this->setMessage($message);
        } else {
            $_POST['csrf'] = $this->data();
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     *
     * @return ValidationInterface
     * Проверяет верность заполнения капчи
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function captcha($data, $message = 'Пожалуйста заполните поле :: reCaptcha'): ValidationInterface
    {
        $captcha = $data ?? false;

        if (!$captcha) {
            $this->setResult(false);
            $this->setMessage($message);

            return $this;
        }

        $remoteAddress = $this->container()->getServer('REMOTE_ADDR') ?? '127.0.0.1';
        $response      = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->captchaSecret() . "&response=" . $captcha . "&remoteip=" . $remoteAddress), true);

        if ($this->captchaSecret() == 'test_success') {
            $response['success'] = true;
        }

        if ($response['success'] === false) {
            $this->setResult(false);
            $this->setMessage($message);
        } else {
            $this->setData($response['success']);
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function isResult(): bool
    {
        return $this->result;
    }

    /**
     * @param bool $result
     */
    protected function setResult(bool $result)
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    protected function message()
    {
        return $this->message;
    }

    /**
     * @param $message
     */
    protected function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    protected function captchaSecret(): string
    {
        return $this->captchaSecret;
    }
}
