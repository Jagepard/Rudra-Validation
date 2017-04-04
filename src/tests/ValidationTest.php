<?php

declare(strict_types = 1);

/**
 * Date: 21.07.16
 * Time: 17:53
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */


use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;
use Rudra\Validation;
use Rudra\IContainer;
use Rudra\Container;


/**
 * Class ValidationTest
 */
class ValidationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @var IContainer
     */
    protected $container;

    protected function setUp(): void
    {
        $this->container  = Container::app();
        $this->validation = new Validation($this->container);
    }

    public function testSet(): void
    {
        $this->validation()->set('String');
        $data = $this->validation()->run();

        $this->assertEquals('String', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testSanitize(): void
    {
        $this->validation()->sanitize(' <p>String</p> ');
        $data = $this->validation()->run();

        $this->assertEquals('String', $data[0]);
        $this->assertNull($data[1]);

        $this->validation()->sanitize(' <p>String</p> ', '<p><a>');
        $data = $this->validation()->run();

        $this->assertEquals('<p>String</p>', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testHash(): void
    {
        $this->validation()->set('123456')->hash();
        $data = $this->validation()->run();

        $this->assertEquals('$/P7XMG2B8gCLzZNXrLASJ9TmWFa3Ek9j0owC5/Pub5CBSR1Aeihs4.QFZmiQK2cou6DgNyCnJuZUCKSh1uTpa.', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testRequired(): void
    {
        $this->validation()->set('')->required();
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation()->set('')->integer()->required();
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо указать число', $data[1]);

        $this->validation()->set('String')->required();
        $data = $this->validation()->run();

        $this->assertEquals('String', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testInteger(): void
    {
        $this->validation()->set('')->required()->integer();
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation()->set('123')->integer();
        $data = $this->validation()->run();

        $this->assertEquals('123', $data[0]);
        $this->assertNull($data[1]);
    }

    public function testMinLength(): void
    {
        $this->validation()->set('')->required()->minLength(5);
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation()->set('12345')->minLength(5);
        $data = $this->validation()->run();

        $this->assertEquals('12345', $data[0]);
        $this->assertNull($data[1]);

        $this->validation()->set('123')->minLength(5);
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Указано слишком мало символов', $data[1]);
    }

    public function testMaxLength(): void
    {
        $this->validation()->set('')->required()->maxLength(5);
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation()->set('12345')->maxLength(5);
        $data = $this->validation()->run();

        $this->assertEquals('12345', $data[0]);
        $this->assertNull($data[1]);

        $this->validation()->set('123456')->maxLength(5);
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Указано слишком много символов', $data[1]);
    }

    public function testEquals(): void
    {
        $this->validation()->set('')->required()->equals('456');
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation()->set('12345')->equals('12345');
        $data = $this->validation()->run();

        $this->assertEquals('12345', $data[0]);
        $this->assertNull($data[1]);

        $this->validation()->set('123')->equals('456');
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Пароли не совпадают', $data[1]);
    }

    public function testEmail(): void
    {
        $this->validation()->set('')->required()->email('user@example.com');
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Необходимо заполнить поле', $data[1]);

        $this->validation()->email('user@example.com');
        $data = $this->validation()->run();

        $this->assertEquals('user@example.com', $data[0]);
        $this->assertNull($data[1]);

        $this->validation()->email('123');
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('Email указан неверно', $data[1]);
    }

    public function testCsrf(): void
    {
        $this->container()->setSession('csrf_token', ['123456']);
        $this->validation()->set('123456')->csrf();
        $data = $this->validation()->run();

        $this->assertEquals('123456', $data[0]);
        $this->assertNull($data[1]);

        $this->validation()->set('123')->csrf();
        $data = $this->validation()->run();

        $this->assertFalse($data[0]);
        $this->assertEquals('csrf', $data[1]);
    }

    /**
     * @return Validation
     */
    public function validation(): Validation
    {
        return $this->validation;
    }

    /**
     * @return IContainer
     */
    public function container(): IContainer
    {
        return $this->container;
    }
}
