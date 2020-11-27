<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Validation\Tests;

use Rudra\Validation\{Validation, ValidationInterface, ValidationFacade};
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class ValidationTest extends PHPUnit_Framework_TestCase
{
    protected ValidationInterface $validation;

    protected function setUp(): void
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.0.1';
        $this->validation       = new Validation();
    }

    public function testSet(): void
    {
        $checked = ValidationFacade::set('String')->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testSanitize(): void
    {
        $checked = ValidationFacade::sanitize(' <p>String</p> ')->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::sanitize(' <p>String</p> ', '<p><a>')->run();
        $this->assertEquals('<p>String</p>', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testHash(): void
    {
        $checked = ValidationFacade::set('123456')->hash()->run();
        $this->assertEquals(
            '$/P7XMG2B8gCLzZNXrLASJ9TmWFa3Ek9j0owC5/Pub5CBSR1Aeihs4.QFZmiQK2cou6DgNyCnJuZUCKSh1uTpa.',
            $checked[0]
        );
        $this->assertNull($checked[1]);
    }

    public function testRequired(): void
    {
        $checked = ValidationFacade::set('')->required()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = ValidationFacade::set('')->integer()->required()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Number is required', $checked[1]);

        $checked = ValidationFacade::set('String')->required()->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testInteger(): void
    {
        $checked = ValidationFacade::set('')->required()->integer()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = ValidationFacade::set('123')->integer()->run();
        $this->assertEquals('123', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testMinLength(): void
    {
        $checked = ValidationFacade::set('')->required()->minLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = ValidationFacade::set('12345')->minLength(5)->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123')->minLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Too few characters specified', $checked[1]);
    }

    public function testMaxLength(): void
    {
        $checked = ValidationFacade::set('')->required()->maxLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = ValidationFacade::set('12345')->maxLength(5)->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123456')->maxLength(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Too many characters specified', $checked[1]);
    }

    public function testEquals(): void
    {
        $checked = ValidationFacade::set('')->required()->equals('456')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = ValidationFacade::set('12345')->equals('12345')->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123')->equals('456')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Values ​​do not match', $checked[1]);
    }

    public function testEmail(): void
    {
        $checked = ValidationFacade::set('')->required()->email('user@example.com')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('You must fill in the field', $checked[1]);

        $checked = ValidationFacade::email('user@example.com')->run();
        $this->assertEquals('user@example.com', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::email('123')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Email is invalid', $checked[1]);
    }

    public function testCsrf(): void
    {
        $_SESSION['csrf_token'][] = '123456';
        $checked = ValidationFacade::set('123456')->csrf($_SESSION['csrf_token'])->run();
        $this->assertEquals('123456', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123')->csrf($_SESSION['csrf_token'])->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('csrf', $checked[1]);
    }

    public function testCapcha(): void
    {
        $checked = ValidationFacade::captcha(null)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Please fill in the field :: reCaptcha', $checked[1]);
        $checked = ValidationFacade::captcha('123')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Please fill in the field :: reCaptcha', $checked[1]);
        $checked = ValidationFacade::captcha('test_success', 'test_success')->run();
        $this->assertTrue($checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testAccess(): void
    {
        $data = [
            'required'  => ValidationFacade::set('')->required()->run(),
            'integer'   => ValidationFacade::set('')->required()->integer()->run(),
            'minLength' => ValidationFacade::set('')->required()->minLength(5)->run(),
            'maxLength' => ValidationFacade::set('')->required()->maxLength(5)->run(),
        ];

        $this->assertFalse(ValidationFacade::checkArray($data));

        $data = [
            'required'  => ValidationFacade::set('123')->required()->run(),
            'integer'   => ValidationFacade::set('123')->required()->integer()->run(),
            'minLength' => ValidationFacade::set('12345')->required()->minLength(5)->run(),
            'maxLength' => ValidationFacade::set('12345')->required()->maxLength(5)->run(),
        ];

        $this->assertTrue(ValidationFacade::checkArray($data));
    }

    public function testGetChecked(): void
    {
        $data = [
            'required'  => ValidationFacade::set('123')->required()->run(),
            'integer'   => ValidationFacade::set('123')->required()->integer()->run(),
            'minLength' => ValidationFacade::set('12345')->required()->minLength(5)->run(),
            'maxLength' => ValidationFacade::set('12345')->required()->maxLength(5)->run(),
        ];

        $checked = ValidationFacade::getChecked($data, ['required']);
        $this->assertCount(3, $checked);
    }

    public function testFlash(): void
    {
        $data = [
            'required'  => ValidationFacade::set('')->required()->run(),
            'integer'   => ValidationFacade::set('')->required()->integer()->run(),
            'minLength' => ValidationFacade::set('')->required()->minLength(5)->run(),
            'maxLength' => ValidationFacade::set('')->required()->maxLength(5)->run(),
        ];

        $alerts = ValidationFacade::getAlerts($data, ['required']);
        $this->assertCount(3, $alerts);
    }
}
