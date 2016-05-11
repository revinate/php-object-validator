<?php

namespace Revinate\ObjectValidator;

/**
 * Created by PhpStorm.
 * User: jasondent
 * Date: 11/05/2016
 * Time: 23:20
 */
class ValidationResultItemTest extends \PHPUnit_Framework_TestCase {

    public function testError() {
        $item = ValidationResultItem::error('test', 'test message', 'value');
        $this->assertInstanceOf('\Revinate\ObjectValidator\ValidationResultItem', $item);
        $this->assertEquals('test', $item->getPath());
        $this->assertEquals('test message', $item->getMessage());
        $this->assertEquals('value', $item->getValue());
        $this->assertTrue($item->isError());
        $this->assertFalse($item->isWarning());
        $this->assertFalse($item->isOk());
        $this->assertEquals(ValidationResultItem::LEVEL_ERROR, $item->getLevel());
    }

    public function testWarning() {
        $item = ValidationResultItem::warning('test', 'test message', 'value');
        $this->assertInstanceOf('\Revinate\ObjectValidator\ValidationResultItem', $item);
        $this->assertEquals('test', $item->getPath());
        $this->assertEquals('test message', $item->getMessage());
        $this->assertEquals('value', $item->getValue());
        $this->assertFalse($item->isError());
        $this->assertTrue($item->isWarning());
        $this->assertFalse($item->isOk());
        $this->assertEquals(ValidationResultItem::LEVEL_WARNING, $item->getLevel());
    }

    public function testOk() {
        $item = ValidationResultItem::ok('test', 'test message', 'value');
        $this->assertInstanceOf('\Revinate\ObjectValidator\ValidationResultItem', $item);
        $this->assertEquals('test', $item->getPath());
        $this->assertEquals('test message', $item->getMessage());
        $this->assertEquals('value', $item->getValue());
        $this->assertFalse($item->isError());
        $this->assertFalse($item->isWarning());
        $this->assertTrue($item->isOk());
        $this->assertEquals(ValidationResultItem::LEVEL_OK, $item->getLevel());
    }

}
