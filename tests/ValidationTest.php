<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

use Rudra\Container;
use Rudra\Validation;
use Rudra\Interfaces\ContainerInterface;
use Rudra\Interfaces\ValidationInterface;
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * Class ValidationTest
 */
class ValidationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var ValidationInterface
     */
    protected $validation;

    protected function setUp(): void
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.0.1';
        $this->container        = Container::app();
        $this->validation       = new Validation($this->container, '123');
    }

    public function testSet(): void
    {
        $this->validation->set('String');
        $data = $this->validation->run();

        $this->assertEquals('String', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testSanitize(): void
    {
        $this->validation->sanitize(' <p>String</p> ');
        $data = $this->validation->run();

        $this->assertEquals('String', $data[0]);
        $this->assertNull($data[1]);

        $this->validation->sanitize(' <p>String</p> ', '<p><a>');
        $data = $this->validation->run();

        $this->assertEquals('<p>String</p>', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testHash(): void
    {
        $this->validation->set('123456')->hash();
        $data = $this->validation->run();

        $this->assertEquals('$/P7XMG2B8gCLzZNXrLASJ9TmWFa3Ek9j0owC5/Pub5CBSR1Aeihs4.QFZmiQK2cou6DgNyCnJuZUCKSh1uTpa.', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testRequired(): void
    {
        $this->validation->set('')->required();
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation->set('')->integer()->required();
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо указать число', $data[1]);

        $this->validation->set('String')->required();
        $data = $this->validation->run();

        $this->assertEquals('String', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testInteger(): void
    {
        $this->validation->set('')->required()->integer();
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation->set('123')->integer();
        $data = $this->validation->run();

        $this->assertEquals('123', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testMinLength(): void
    {
        $this->validation->set('')->required()->minLength(5);
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation->set('12345')->minLength(5);
        $data = $this->validation->run();

        $this->assertEquals('12345', $data[0]);
        $this->assertNull($data[1]);

        $this->validation->set('123')->minLength(5);
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Указано слишком мало символов', $data[1]);
    }

    public function testMaxLength(): void
    {
        $this->validation->set('')->required()->maxLength(5);
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation->set('12345')->maxLength(5);
        $data = $this->validation->run();

        $this->assertEquals('12345', $data[0]);
        $this->assertNull($data[1]);

        $this->validation->set('123456')->maxLength(5);
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Указано слишком много символов', $data[1]);
    }

    public function testEquals(): void
    {
        $this->validation->set('')->required()->equals('456');
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation->set('12345')->equals('12345');
        $data = $this->validation->run();

        $this->assertEquals('12345', $data[0]);
        $this->assertNull($data[1]);

        $this->validation->set('123')->equals('456');
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Пароли не совпадают', $data[1]);
    }

    public function testEmail(): void
    {
        $this->validation->set('')->required()->email('user@example.com');
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation->email('user@example.com');
        $data = $this->validation->run();

        $this->assertEquals('user@example.com', $data[0]);
        $this->assertNull($data[1]);

        $this->validation->email('123');
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Email указан неверно', $data[1]);
    }

    public function testCsrf(): void
    {
        $this->container->setSession('csrf_token', ['123456']);
        $this->validation->set('123456')->csrf();
        $data = $this->validation->run();

        $this->assertEquals('123456', $data[0]);
        $this->assertNull($data[1]);

        $this->validation->set('123')->csrf();
        $data = $this->validation->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('csrf', $data[1]);
    }

    public function testCapcha(): void
    {
        $data = $this->validation->captcha(null)->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Пожалуйста заполните поле :: reCaptcha', $data[1]);

        $data = $this->validation->captcha('123')->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Пожалуйста заполните поле :: reCaptcha', $data[1]);

        $this->validation = new Validation($this->container, 'test_success');
        $data             = $this->validation->captcha('test_success')->run();

        $this->assertTrue($data[0]);
        $this->assertNull($data[1]);
    }

    public function testAccess(): void
    {
        $validation = [
            'required'  => $this->validation->set('')->required()->run(),
            'integer'   => $this->validation->set('')->required()->integer()->run(),
            'minLength' => $this->validation->set('')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('')->required()->maxLength(5)->run()
        ];

        $this->assertFalse($this->validation->access($validation));

        $validation = [
            'required'  => $this->validation->set('123')->required()->run(),
            'integer'   => $this->validation->set('123')->required()->integer()->run(),
            'minLength' => $this->validation->set('12345')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('12345')->required()->maxLength(5)->run()
        ];

        $this->assertTrue($this->validation->access($validation));
    }

    public function testGet(): void
    {
        $validation = [
            'required'  => $this->validation->set('123')->required()->run(),
            'integer'   => $this->validation->set('123')->required()->integer()->run(),
            'minLength' => $this->validation->set('12345')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('12345')->required()->maxLength(5)->run()
        ];

        $validated = $this->validation->get($validation, ['required']);

        $this->assertCount(3, $validated);
    }

    public function testFlash(): void
    {
        $validation = [
            'required'  => $this->validation->set('')->required()->run(),
            'integer'   => $this->validation->set('')->required()->integer()->run(),
            'minLength' => $this->validation->set('')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('')->required()->maxLength(5)->run()
        ];

        $flash = $this->validation->flash($validation, ['required']);

        $this->assertCount(3, $flash);
    }
}
