<?php

use \Revinate\ObjectValidator\ValidationResult;
use \Revinate\ObjectValidator\ValidationResultItem;

/**
 * Created by PhpStorm.
 * User: jasondent
 * Date: 12/05/2016
 * Time: 00:12
 */
class ValidationResultTest extends PHPUnit_Framework_TestCase {

    public function testCreate() {
        $object = (object)['field' => 'value'];
        $result = ValidationResult::create($object);
        $this->assertInstanceOf('\Revinate\ObjectValidator\ValidationResult', $result);
    }
    
    public function testAddItem() {
        $object = (object)['field2' => 'value'];
        $warning = ValidationResultItem::warning('field', 'field should exist', null);
        $ok = ValidationResultItem::ok('field2', 'field must exist', null);
        $error = ValidationResultItem::error('field', 'field must exist', null);
        $result = ValidationResult::create($object);

        $result->addItem($ok);
        $this->assertTrue($result->isValid());

        $result->addItem($warning);
        $this->assertTrue($result->isValid());

        $result->addItem($error);
        $this->assertFalse($result->isValid());
    }

    public function testGetItems() {
        $object = (object)['field2' => 'value'];
        $warning = ValidationResultItem::warning('field', 'field should exist', null);
        $ok = ValidationResultItem::ok('field2', 'field must exist', null);
        $error = ValidationResultItem::error('field', 'field must exist', null);
        $result = ValidationResult::create($object);

        $result->addItem($ok);
        $this->assertCount(1, $result->getValidationItems());

        $result->addItem($warning);
        $result->addItem($warning);
        $this->assertCount(3, $result->getValidationItems());

        $result->addItem($error);
        $result->addItem($error);
        $result->addItem($error);
        $this->assertCount(6, $result->getValidationItems());

        $this->assertCount(3, $result->getErrors());
        $this->assertCount(2, $result->getWarnings());
        $this->assertCount(1, $result->getOks());
    }

}
