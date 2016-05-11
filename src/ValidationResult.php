<?php
/**
 * User: jasondent
 * Date: 11/05/2016
 * Time: 21:41
 */

namespace Revinate\ObjectValidator;


class ValidationResult {
    /** @var  mixed */
    protected $object;
    
    /** @var  boolean */
    protected $valid = true;
    /** @var ValidationResultItem[] */
    protected $validationItems = [];

    /**
     * ValidationResult constructor.
     * @param mixed $object
     */
    public function __construct($object) { $this->object = $object; }

    /**
     * @param mixed $object
     * @return static
     */
    public static function create($object) {
        return new static($object);
    }
    
    /**
     * @param ValidationResultItem $item
     * @return $this
     */
    public function addItem(ValidationResultItem $item) {
        $this->validationItems[] = $item;
        $this->valid = $this->valid && ! $item->isError();
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return $this->valid;
    }

    /**
     * @return ValidationResultItem[]
     */
    public function getValidationItems() {
        return $this->validationItems;
    }

    /**
     * Get the set of errors
     * @return ValidationResultItem[]
     */
    public function getErrors() {
        return array_filter($this->validationItems, function(ValidationResultItem $item) {
            return $item->isError();
        });
    }

    /**
     * Get the set of warnings
     * @return ValidationResultItem[]
     */
    public function getWarnings() {
        return array_filter($this->validationItems, function(ValidationResultItem $item) {
            return $item->isWarning();
        });
    }

    /**
     * Get the set of ok items
     * @return ValidationResultItem[]
     */
    public function getOks() {
        return array_filter($this->validationItems, function(ValidationResultItem $item) {
            return $item->isOk();
        });
    }
}
