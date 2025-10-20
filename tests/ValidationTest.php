<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Validation\Tests;

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;
use Rudra\Validation\{Validation, ValidationInterface, ValidationFacade};

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

    public function testRequired(): void
    {
        $checked = ValidationFacade::set('')->required()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::set('')->integer()->required()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Укажите целое число', $checked[1]);

        $checked = ValidationFacade::set('String')->required()->run();
        $this->assertEquals('String', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testNumeric(): void
    {
        $checked = ValidationFacade::set('')->required()->numeric()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::set('123')->numeric()->run();
        $this->assertEquals('123', $checked[0]);
        $this->assertNull($checked[1]);
    }

    public function testInteger(): void
    {
        $checked = ValidationFacade::set('')->required()->integer()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::sanitize('123')->integer()->run();
        $this->assertEquals('123', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::sanitize('123,56')->integer()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Укажите целое число', $checked[1]);
    }

    public function testMinLength(): void
    {
        $checked = ValidationFacade::set('')->required()->min(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::set('12345')->min(5)->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123')->min(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Слишком мало символов', $checked[1]);
    }

    public function testMaxLength(): void
    {
        $checked = ValidationFacade::set('')->required()->max(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::set('12345')->max(5)->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123456')->max(5)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Слишком много символов', $checked[1]);
    }

    public function testEquals(): void
    {
        $checked = ValidationFacade::set('')->required()->equals('456')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::set('12345')->equals('12345')->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123')->equals('456')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Значение не совпадает', $checked[1]);
    }

    public function testBetween(): void
    {
        $checked = ValidationFacade::set('')->required()->between(1, 10)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::sanitize('3')->between(1, 10)->run();
        $this->assertEquals('3', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::sanitize('123')->between(1, 10)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Значение выходит за пределы диапазона', $checked[1]);
    }

    public function testRegex(): void
    {
        // Проверка: значение не соответствует паттерну
        $checked = ValidationFacade::sanitize('bad_input')->regex('/^[a-z]+$/')->run();
        $this->assertFalse($checked[0]); // Проверка не пройдена
        $this->assertEquals('Неверный формат', $checked[1]); // Сообщение по умолчанию

        // Проверка: значение соответствует паттерну
        $checked = ValidationFacade::sanitize('goodinput')->regex('/^[a-z]+$/')->run();
        $this->assertEquals('goodinput', $checked[0]); // Возвращается очищенное значение
        $this->assertNull($checked[1]); // Нет ошибки

        // Проверка: значение соответствует паттерну (числа)
        $checked = ValidationFacade::sanitize('12345')->regex('/^\d+$/')->run();
        $this->assertEquals('12345', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: значение не соответствует паттерну (числа)
        $checked = ValidationFacade::sanitize('abc123')->regex('/^\d+$/')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Неверный формат', $checked[1]);
    }

    public function testDate(): void
    {
        // Проверка: значение не соответствует формату по умолчанию (Y-m-d)
        $checked = ValidationFacade::sanitize('31/12/2024')->date()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Дата указана неверно', $checked[1]);

        // Проверка: значение соответствует формату по умолчанию (Y-m-d)
        $checked = ValidationFacade::sanitize('2024-12-31')->date()->run();
        $this->assertEquals('2024-12-31', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: значение не соответствует формату d/m/Y
        $checked = ValidationFacade::sanitize('2024-12-31')->date('d/m/Y')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Дата указана неверно', $checked[1]);

        // Проверка: значение соответствует формату d/m/Y
        $checked = ValidationFacade::sanitize('31/12/2024')->date('d/m/Y')->run();
        $this->assertEquals('31/12/2024', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: кастомное сообщение об ошибке
        $customMessage = 'Please enter date in DD/MM/YYYY format';
        $checked = ValidationFacade::sanitize('invalid-date')->date('d/m/Y', $customMessage)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals($customMessage, $checked[1]);

        // Проверка: високосный год, валидная дата
        $checked = ValidationFacade::sanitize('2024-02-29')->date()->run();
        $this->assertEquals('2024-02-29', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: невисокосный год, невалидная дата (29 февраля)
        $checked = ValidationFacade::sanitize('2023-02-29')->date()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Дата указана неверно', $checked[1]);
    }

    public function testCustom(): void
    {
        // Проверка: кастомная валидация возвращает false
        $callback = fn($value) => strlen($value) > 5; // Требуем длину больше 5
        $checked = ValidationFacade::sanitize('short')->custom($callback)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Ошибка валидации', $checked[1]);

        // Проверка: кастомная валидация возвращает true
        $callback = fn($value) => strlen($value) > 5; // Требуем длину больше 5
        $checked = ValidationFacade::sanitize('verylong')->custom($callback)->run();
        $this->assertEquals('verylong', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: кастомная валидация числа (чётное)
        $callback = fn($value) => (int)$value % 2 === 0;
        $checked = ValidationFacade::sanitize('4')->custom($callback)->run();
        $this->assertEquals('4', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: кастомная валидация числа (нечётное)
        $callback = fn($value) => (int)$value % 2 === 0;
        $checked = ValidationFacade::sanitize('5')->custom($callback)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Ошибка валидации', $checked[1]);

        // Проверка: кастомное сообщение об ошибке
        $customMessage = 'Value must be greater than 10';
        $callback = fn($value) => (int)$value > 10;
        $checked = ValidationFacade::sanitize('5')->custom($callback, $customMessage)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals($customMessage, $checked[1]);
    }

    public function testIn(): void
    {
        // Проверка: значение не содержится в разрешённом списке (строгое сравнение: '1' !== 1)
        $checked = ValidationFacade::sanitize('1')->in([1, 2, 3])->run(); // <--- без `true`
        $this->assertFalse($checked[0]);
        $this->assertEquals('Выбрано неверное значение', $checked[1]);

        // Проверка: значение содержится в разрешённом списке
        $checked = ValidationFacade::sanitize('two')->in(['one', 'two', 'three'])->run();
        $this->assertEquals('two', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: значение проходит строгое сравнение (1 === 1)
        $checked = ValidationFacade::set(1)->in([1, 2, 3])->run();
        $this->assertEquals('1', $checked[0]);
        $this->assertNull($checked[1]);

        // Проверка: кастомное сообщение об ошибке
        $customMessage = 'Please select a valid option';
        $checked = ValidationFacade::sanitize('invalid')->in(['valid1', 'valid2'], $customMessage)->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals($customMessage, $checked[1]);
    }

    public function testEmail(): void
    {
        $checked = ValidationFacade::set('')->required()->email('user@example.com')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::email('user@example.com')->run();
        $this->assertEquals('user@example.com', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::email('123')->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Email указан неверно', $checked[1]);
    }

    public function testUrl(): void
    {
        $checked = ValidationFacade::set('')->required()->url()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Поле должно быть заполнено', $checked[1]);

        $checked = ValidationFacade::sanitize("https://www.example.com/path?query=value&other=1#section")->url()->run();
        $this->assertEquals("https://www.example.com/path?query=value&other=1#section", $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::sanitize('123')->url()->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Некорректный URL-адрес', $checked[1]);
    }

    public function testCsrf(): void
    {
        $_SESSION['csrf_token'][] = '123456';
        $checked = ValidationFacade::set('123456')->csrf($_SESSION['csrf_token'])->run();
        $this->assertEquals('123456', $checked[0]);
        $this->assertNull($checked[1]);

        $checked = ValidationFacade::set('123')->csrf($_SESSION['csrf_token'])->run();
        $this->assertFalse($checked[0]);
        $this->assertEquals('Invalid CSRF token', $checked[1]);
    }

    public function testApprove(): void
    {
        $data = [
            'required' => ValidationFacade::set('')->required()->run(),
            'integer'  => ValidationFacade::set('')->required()->integer()->run(),
            'min'      => ValidationFacade::set('')->required()->min(5)->run(),
            'max'      => ValidationFacade::set('')->required()->max(5)->run(),
        ];

        $this->assertFalse(ValidationFacade::approve($data));

        $data = [
            'required' => ValidationFacade::set('123')->required()->run(),
            'integer'  => ValidationFacade::set('123')->required()->integer()->run(),
            'min'      => ValidationFacade::set('12345')->required()->min(5)->run(),
            'max'      => ValidationFacade::set('12345')->required()->max(5)->run(),
        ];

        $this->assertTrue(ValidationFacade::approve($data));
    }

    public function testGetValidated(): void
    {
        $data = [
            'required' => ValidationFacade::set('123')->required()->run(),
            'integer'  => ValidationFacade::set('123')->required()->integer()->run(),
            'min'      => ValidationFacade::set('12345')->required()->min(5)->run(),
            'max'      => ValidationFacade::set('12345')->required()->max(5)->run(),
        ];

        $checked = ValidationFacade::getValidated($data, ['required']);
        $this->assertCount(3, $checked);
    }

    public function testErrors(): void
    {
        $data = [
            'required' => ValidationFacade::set('')->required()->run(),
            'integer'  => ValidationFacade::set('asd')->required()->integer()->run(),
            'min'      => ValidationFacade::set('')->required()->min(5)->run(),
            'max'      => ValidationFacade::set('')->required()->max(5)->run(),
        ];

        $errors = ValidationFacade::getErrors($data, ['required']);
        $this->assertCount(3, $errors);
    }
}
