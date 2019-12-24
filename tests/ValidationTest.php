<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation\Tests;

use Rudra\Validation\{Validation, ValidationInterface};
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class ValidationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ValidationInterface
     */
    protected $validation;

    protected function setUp(): void
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.0.1';
        $this->validation       = new Validation();
    }

    public function testSet(): void
    {
        $checked = $this->validation->set('String')->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testSanitize(): void
    {
        $checked = $this->validation->sanitize(' <p>String</p> ')->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = $this->validation->sanitize(' <p>String</p> ', '<p><a>')->run();
        $this->assertEquals('<p>String</p>', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testHash(): void
    {
        $checked = $this->validation->set('123456')->hash()->run();
        $this->assertEquals(
            '$/P7XMG2B8gCLzZNXrLASJ9TmWFa3Ek9j0owC5/Pub5CBSR1Aeihs4.QFZmiQK2cou6DgNyCnJuZUCKSh1uTpa.',
            $checked[0]
        );
        $this->assertNull($checked[1]);
    }

    public function testRequired(): void
    {
        $checked = $this->validation->set('')->required()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = $this->validation->set('')->integer()->required()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Number is required', $checked[1]);

        $checked = $this->validation->set('String')->required()->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testInteger(): void
    {
        $checked = $this->validation->set('')->required()->integer()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = $this->validation->set('123')->integer()->run();
        $this->assertEquals('123', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testMinLength(): void
    {
        $checked = $this->validation->set('')->required()->minLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = $this->validation->set('12345')->minLength(5)->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = $this->validation->set('123')->minLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Too few characters specified', $checked[1]);
    }

    public function testMaxLength(): void
    {
        $checked = $this->validation->set('')->required()->maxLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = $this->validation->set('12345')->maxLength(5)->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = $this->validation->set('123456')->maxLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Too many characters specified', $checked[1]);
    }

    public function testEquals(): void
    {
        $checked = $this->validation->set('')->required()->equals('456')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = $this->validation->set('12345')->equals('12345')->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = $this->validation->set('123')->equals('456')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Values ​​do not match', $checked[1]);
    }

    public function testEmail(): void
    {
        $checked = $this->validation->set('')->required()->email('user@example.com')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = $this->validation->email('user@example.com')->run();
        $this->assertEquals('user@example.com', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = $this->validation->email('123')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Email is invalid', $checked[1]);
    }

    public function testCsrf(): void
    {
        $_SESSION['csrf_token'][] = '123456';
        $checked = $this->validation->set('123456')->csrf($_SESSION['csrf_token'])->run();
        $this->assertEquals('123456', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = $this->validation->set('123')->csrf($_SESSION['csrf_token'])->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('csrf', $checked[1]);
    }

    public function testCapcha(): void
    {
        $checked = $this->validation->captcha(null)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Please fill in the field :: reCaptcha', $checked[1]);
        $checked = $this->validation->captcha('123')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Please fill in the field :: reCaptcha', $checked[1]);
        $checked = $this->validation->captcha('test_success', 'test_success')->run();
        $this->assertTrue($checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testAccess(): void
    {
        $data = [
            'required'  => $this->validation->set('')->required()->run(),
            'integer'   => $this->validation->set('')->required()->integer()->run(),
            'minLength' => $this->validation->set('')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('')->required()->maxLength(5)->run(),
        ];

        $this->assertFalse($this->validation->checkArray($data));

        $data = [
            'required'  => $this->validation->set('123')->required()->run(),
            'integer'   => $this->validation->set('123')->required()->integer()->run(),
            'minLength' => $this->validation->set('12345')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('12345')->required()->maxLength(5)->run(),
        ];

        $this->assertTrue($this->validation->checkArray($data));
    }

    public function testGetChecked(): void
    {
        $data = [
            'required'  => $this->validation->set('123')->required()->run(),
            'integer'   => $this->validation->set('123')->required()->integer()->run(),
            'minLength' => $this->validation->set('12345')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('12345')->required()->maxLength(5)->run(),
        ];

        $checked = $this->validation->getChecked($data, ['required']);
        $this->assertCount(3, $checked);
    }

    public function testFlash(): void
    {
        $data = [
            'required'  => $this->validation->set('')->required()->run(),
            'integer'   => $this->validation->set('')->required()->integer()->run(),
            'minLength' => $this->validation->set('')->required()->minLength(5)->run(),
            'maxLength' => $this->validation->set('')->required()->maxLength(5)->run(),
        ];

        $alerts = $this->validation->getAlerts($data, ['required']);
        $this->assertCount(3, $alerts);
    }
}
