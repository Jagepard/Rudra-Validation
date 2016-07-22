<?php

/**
 * Date: 21.07.16
 * Time: 17:53
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

/**
 * Class ValidationTest
 */
class ValidationTest extends PHPUnit_Framework_TestCase
{
    protected $v;

    /**
     * Проверяем валидацию чисел
     */
    public function testAssertInteger()
    {
        $inputData = 55;

        $res = [
            $this->v()->set($inputData)->integer()->v(),
        ];

        $this->assertInternalType("int", $res[0][0]);
        $this->assertNull($res[0][1]);

        $this->assertTrue($this->v()->access($res));
    }

    /**
     * Проверяем валидацию чисел
     */
    public function testFailureInteger()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->integer()->v(),
        ];

        $this->assertFalse($res[0][0]);
        $this->assertInternalType("string", $res[0][1]);

        $this->assertFalse($this->v()->access($res));
    }

    /**
     * Проверяем валидацию минимального
     * количества символов
     */
    public function testAssertMinLenght()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->minLenght(5)->v(),
        ];

        $this->assertInternalType("string", $res[0][0]);
        $this->assertNull($res[0][1]);

        $this->assertTrue($this->v()->access($res));
    }

    /**
     * Проверяем валидацию минимального
     * количества символов
     */
    public function testFailureMinLenght()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->minLenght(7)->v(),
        ];

        $this->assertFalse($res[0][0]);
        $this->assertInternalType("string", $res[0][1]);

        $this->assertFalse($this->v()->access($res));
    }


    /**
     * Проверяем валидацию минимального
     * количества символов
     */
    public function testAssertMaxLenght()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->maxLenght(7)->v(),
        ];

        $this->assertInternalType("string", $res[0][0]);
        $this->assertNull($res[0][1]);

        $this->assertTrue($this->v()->access($res));
    }

    /**
     * Проверяем валидацию минимального
     * количества символов
     */
    public function testFailureMaxLenght()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->maxLenght(5)->v(),
        ];

        $this->assertFalse($res[0][0]);
        $this->assertInternalType("string", $res[0][1]);

        $this->assertFalse($this->v()->access($res));
    }

    /**
     * Проверяем валидацию необходимого
     * количества символов
     */
    public function testAssertRequired()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->required()->v(),
        ];

        $this->assertInternalType("string", $res[0][0]);
        $this->assertNull($res[0][1]);

        $this->assertTrue($this->v()->access($res));
    }

    /**
     * Проверяем валидацию необходимого
     * количества символов
     */
    public function testFailureRequired()
    {
        $inputData = '';

        $res = [
            $this->v()->set($inputData)->required()->v(),
        ];

        $this->assertFalse($res[0][0]);
        $this->assertInternalType("string", $res[0][1]);

        $this->assertFalse($this->v()->access($res));
    }

    /**
     * Проверяем валидацию одинаковых данных
     */
    public function testAssertEquals()
    {
        $inputData = [123, 123];

        $res = [
            $this->v()->set($inputData[0])->equals($inputData)->v(),
        ];

        $this->assertInternalType("int", $res[0][0]);
        $this->assertNull($res[0][1]);

        $this->assertTrue($this->v()->access($res));
    }

    /**
     * Проверяем валидацию одинаковых данных
     */
    public function testFailureEquals()
    {
        $inputData = [123, 12345];

        $res = [
            $this->v()->set($inputData[0])->equals($inputData)->v(),
        ];

        $this->assertFalse($res[0][0]);
        $this->assertInternalType("string", $res[0][1]);

        $this->assertFalse($this->v()->access($res));
    }

    /**
     * Проверяем валидацию почты
     */
    public function testAssertEmail()
    {
        $inputData = 'as@as.as';

        $res = [
            $this->v()->email($inputData)->v(),
        ];

        $this->assertInternalType("string", $res[0][0]);
        $this->assertNull($res[0][1]);

        $this->assertTrue($this->v()->access($res));
    }

    /**
     * Проверяем валидацию почты
     */
    public function testFailureEmail()
    {
        $inputData = 'asasas.as';

        $res = [
            $this->v()->email($inputData)->v(),
        ];

        $this->assertFalse($res[0][0]);
        $this->assertInternalType("string", $res[0][1]);

        $this->assertFalse($this->v()->access($res));
    }

    /**
     * Проверяем флеш сообщения
     */
    public function testFlash()
    {
        $inputData = 'string';

        $res = [
            $this->v()->set($inputData)->integer()->v(),
        ];

        foreach ($this->v()->flash($res, []) as $flash) {
            $this->assertInternalType("string", $flash);
        }
    }

    /**
     * Проверяем получение данных
     */
    public function testGet()
    {
        $res = [
            $this->v()->set(65)->integer()->v(),
            'deleted' => 'deleted'
        ];

        foreach ($this->v()->get($res, ['deleted']) as $get) {
            $this->assertInternalType("int", $get);
        }
    }

    protected function setUp()
    {
        $this->v = new \Lingam\Validation();
    }

    protected function tearDown()
    {
        $this->v = null;
    }

    public function v()
    {
        return $this->v;
    }
}