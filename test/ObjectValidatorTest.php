<?php

use \Revinate\ObjectValidator\ObjectValidator;
use \Revinate\Sequence\fn;

/**
 * User: jasondent
 * Date: 11/05/2016
 * Time: 21:25
 */
class ObjectValidatorTest extends PHPUnit_Framework_TestCase {

    public function testCreate() {
        $validator = ObjectValidator::create();
        $this->assertInstanceOf('\Revinate\ObjectValidator\ObjectValidator', $validator);
    }

    public function testMust() {
        $validator = ObjectValidator::create()
            ->must('question', fn\fnIsEmpty())
            ->must('answer', fn\fnIsEqual(42));

        $object = ['answer' => 42, 'question' => null];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());

        $object = (object)['answer' => 42, 'question' => null];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());

        $object = ['answer' => 41, 'question' => null];
        $result = $validator->validate($object);
        $this->assertFalse($result->isValid());
    }

    public function testShould() {
        $validator = ObjectValidator::create()
            ->should('question', fn\fnIsEmpty())
            ->should('answer', fn\fnIsEqual(42));

        $object = ['answer' => 42, 'question' => null];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());

        $object = (object)['answer' => 42, 'question' => null];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());

        $object = ['answer' => 41, 'question' => null];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());
    }

    public function testNestedObject() {
        $validator = ObjectValidator::create()
            ->should('question', fn\fnIsEmpty())
            ->should('answer', fn\fnIsEqual(42))
            ->must('author/name', fn\fnIsEqual('Adams'))
        ;
        $this->assertEquals('.', $validator->getPathSeparator());
        $validator->setPathSeparator('/');

        $object = ['answer' => 42, 'question' => null, 'author' => ['name' => 'Adams', 'age' => 42]];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());

        $object = ['answer' => 42, 'question' => null, 'author' => ['name' => 'Adams', 'age' => 42]];
        $result = $validator->validate($object);
        $this->assertTrue($result->isValid());

        $fnIsValid = $validator->isValidFunction();
        $this->assertTrue($fnIsValid($object));
        $this->assertFalse($fnIsValid(null));
        $this->assertFalse($fnIsValid(['answer' => 42, 'question' => null, 'author' => ['name' => 'D. Adams', 'age' => 42]]));

        $fnValidate = $validator->validationFunction();
        $this->assertTrue($fnValidate($object)->isValid());
        $this->assertFalse($fnValidate(null)->isValid());
    }

    public function testStopOnError() {
        $validator = ObjectValidator::create();
        $this->assertFalse($validator->isStopOnError());

        $validator
            ->must('question', fn\fnIsEmpty(), 'Question must be unknown')
            ->must('answer', fn\fnIsEqual(42), 'Answer should be 42')
            ->should('author/name', fn\fnIsEqual('Adams'), 'Author name must be Adams')
        ;

        $object = ['answer' => 43, 'question' => null, 'author' => ['name' => 'Adams', 'age' => 42]];
        $result = $validator->validate($object);
        $this->assertCount(1, $result->getErrors());
        $this->assertCount(1, $result->getWarnings());
        $this->assertCount(1, $result->getOks());

        // Now turn on stop
        $validator->setStopOnError(true);
        $result = $validator->validate($object);
        $this->assertCount(1, $result->getErrors());
        $this->assertCount(0, $result->getWarnings());
        $this->assertCount(1, $result->getOks());
    }


}
