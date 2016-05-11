<?php

namespace Revinate\ObjectValidator;

use Revinate\GetterSetter as gs;

class ObjectValidator {

    /** @var  \Closure[] */
    protected $testFunctions = [];
    protected $stopOnError   = false;
    protected $pathSeparator = '.';
    
    public function must($path, $predicate, $message = null) {
        $this->testFunctions[] = function($object) use ($path, $predicate, $message) {
            $value = $this->getFieldFromPath($object, $path);
            if ($predicate($value)) {
                return ValidationResultItem::ok($path, $message, $value);
            } 
            return ValidationResultItem::error($path, $message, $value);
        };
        return $this;
    }

    public function should($path, $predicate, $message = null) {
        $this->testFunctions[] = function($object) use ($path, $predicate, $message) {
            $value = $this->getFieldFromPath($object, $path);
            if ($predicate($value)) {
                return ValidationResultItem::ok($path, $message, $value);
            }
            return ValidationResultItem::warning($path, $message, $value);
        };
        return $this;
    }

    /**
     * @param mixed $object
     * @return ValidationResult
     */
    public function validate($object) {
        $result = ValidationResult::create($object);
        foreach ($this->testFunctions as $fn) {
            $result->addItem($fn($object));
            if ($this->stopOnError && ! $result->isValid()) {
                break;
            }
        }
        return $result;
    }

    /**
     * @return boolean
     */
    public function isStopOnError() {
        return $this->stopOnError;
    }

    /**
     * @param boolean $stopOnError
     */
    public function setStopOnError($stopOnError) {
        $this->stopOnError = $stopOnError;
    }

    /**
     * Return a function that can be used to get the validation results.
     * @return \Closure -- fn($object) => ValidationResult
     */
    public function validationFunction() {
        return function($object) {
            return $this->validate($object);    
        };
    }

    /**
     * Returns a function that can be used to test if an object is valid.
     * @return \Closure -- fn($object) => boolean
     */
    public function isValidFunction() {
        return function($object) {
            return $this->validate($object)->isValid();
        };
    }
    
    /**
     * @return static
     */
    public static function create() {
        return new static();
    }

    /**
     * This function is used to extract values from an object.  Override this function if
     * you wish to use a different technique.
     * 
     * @param mixed $object
     * @param string $path
     * @return mixed|null
     */
    protected function getFieldFromPath($object, $path) {
        return gs\get($object, $path, null, $this->pathSeparator);
    }

    /**
     * @return string
     */
    public function getPathSeparator() {
        return $this->pathSeparator;
    }

    /**
     * @param string $pathSeparator
     */
    public function setPathSeparator($pathSeparator) {
        $this->pathSeparator = $pathSeparator;
    }
}