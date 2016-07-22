<?php declare(strict_types = 1);

namespace Lingam;

    /**
     * Date: 03.02.16
     * Time: 18:13
     * @author    : Korotkov Danila <dankorot@gmail.com>
     * @copyright Copyright (c) 2016, Korotkov Danila
     * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
     */

/**
 * Class Validation
 * @package Core
 * Класс валидации данных
 */
class Validation
{
    /**
     * @var string
     */
    public $captchaSecret;

    /**
     * @var array
     * Для данных проходящих валидацию
     */
    protected $data;

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
    protected $res = true;

    /**
     * Validation constructor.
     * @param $captchaSecret
     */
    public function __construct($captchaSecret = null)
    {
        $this->captchaSecret = $captchaSecret;
    }

    /**
     * @param $data
     * @return Validation
     * Устанавливаем данные без обработки
     */
    public function set($data): Validation
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param      $data
     * @param null $allowableTags
     * @return Validation
     * Очищает входящие параметры от ненужных данных
     */
    public function sanitize($data, $allowableTags = null): Validation
    {
        $this->data = strip_tags(trim($data), $allowableTags);

        return $this;
    }

    /**
     * @param string $salt
     * @param int $iterationCount
     * @return Validation
     */
    public function hash(string $salt, int $iterationCount = 13): Validation
    {
        $this->data = substr(crypt($this->data . $salt, '$6$rounds=' . $iterationCount), 14);

        return $this;
    }

    /**
     * @return array
     * Собирает результат работы методов класса
     */
    public function v(): array
    {
        if ($this->isRes()) {
            return [$this->getData(), null];

        } else {

            $result = [false, $this->getMessage()];
            $this->setMessage(null);
            $this->setRes(true);

            return $result;
        }
    }


    /**
     * @param $data
     * @return bool
     * Проверяет все результаты собранные в массив
     */
    public function access($data)
    {
        foreach ($data as $item) {
            if ($item[0] === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $data
     * @param array $exludedKeys
     * @return mixed
     * Возвращает обработанные и проверенные данные
     * исключая при этом элементы массива $exludedKeys
     */
    public function get(array $data, array $exludedKeys)
    {
        foreach ($data as $k => $v) {
            $res[$k] = $v[0];
        }

        foreach ($exludedKeys as $key) {
            if (isset($res[$key])) {
                unset($res[$key]);
            }
        }

        return isset($res) ? $res : [];;
    }

    /**
     * @param $data
     * @param $exludedKeys
     * @return mixed
     * Возвращает массив ошибок
     * исключая при этом элементы массива $exludedKeys
     */
    public function flash($data, $exludedKeys)
    {
        foreach ($data as $k => $v) {
            if (isset($v[1])) {
                $res[$k] = $v[1];
            }
        }

        foreach ($exludedKeys as $key) {
            if (isset($res[$key])) {
                unset($res[$key]);
            }
        }

        return isset($res) ? $res : [];
    }

    /**
     * @param string $message
     * @return Validation
     * Проверяет являются ли данные числом,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function integer(string $message = 'Необходимо указать число'): Validation
    {
        if (!$this->isRes()) return $this;

        $this->setRes((is_numeric($this->getData())) ? true : false);

        if (!$this->isRes()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     * @return Validation
     * Проверяет соответствуют ли данные минимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function minLenght($data, string $message = 'Указано слишком мало символов'): Validation
    {
        if (!$this->isRes()) return $this;

        $this->setRes((mb_strlen($this->getData()) > $data) ? true : false);

        if (!$this->isRes()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     * @return Validation
     * Проверяет соответствуют ли данные максимальной длинне,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function maxLenght($data, string $message = 'Указано слишком много символов'): Validation
    {
        if (!$this->isRes()) return $this;

        $this->setRes((mb_strlen($this->getData()) < $data) ? true : false);

        if (!$this->isRes()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param string $message
     * @return Validation
     * Проверяет необходимость заполнения поля - не меннее 1 символа,
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function required(string $message = 'Необходимо заполнить поле'): Validation
    {
        if (!$this->isRes()) return $this;

        $this->setRes((mb_strlen($this->data) > 0) ? true : false);

        if (!$this->isRes()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     * @return Validation
     * Проверяет эквивалентность введенных данных
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function equals($data, string $message = 'Пароли не совпадают'): Validation
    {
        if (!$this->isRes()) return $this;

        $this->setRes(($data[0] == $data[1]) ? true : false);

        if (!$this->isRes()) {
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     * @return Validation
     * Проверяет email на соответствие
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function email($data, string $message = 'Email указан неверно'): Validation
    {
        $this->set(filter_var($data, FILTER_VALIDATE_EMAIL));

        if (!$this->getData()) {
            $this->setRes(false);
            $this->setMessage($message);
        }

        return $this;
    }

    /**
     * @param        $data
     * @param string $message
     * @return $this
     * Проверяет верность заполнения капчи
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function captcha($data, $message = 'Пожалуйста заполните поле :: reCaptcha')
    {
        $captcha = false;

        if (isset($data)) $captcha = $data;

        if (!$captcha) {
            $this->res = false;
            $this->message = $message;
            return $this;
        }

        $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->captchaSecret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);

        if ($response['success'] == false) {
            $this->res = false;
            $this->message = $message;
        } else {
            $this->data = $response['success'];
        }

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     * Проверяет верность данных csrf защиты
     * в случае прохождения результат проверки передается далее,
     * если нет, то передает сообщение об ошибке в $this->message
     * и $this->res = false
     */
    public function csrf($message = 'csrf')
    {
        if (!in_array($this->data, $_SESSION['csrf_token'])) {
            $this->data = $_SESSION['csrf_token'][0];
            $this->res = false;
            $this->message = $message;
        } else {
            $_POST['csrf'] = $this->data;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isRes(): bool
    {
        return $this->res;
    }

    /**
     * @param bool $res
     */
    public function setRes(bool $res)
    {
        $this->res = $res;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
