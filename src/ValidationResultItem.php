<?php
/**
 * User: jasondent
 * Date: 11/05/2016
 * Time: 21:57
 */

namespace Revinate\ObjectValidator;


class ValidationResultItem {
    const LEVEL_ERROR = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_OK = 'ok';

    /** @var  string */
    protected $level;
    /** @var  string|string[] */
    protected $path;
    /** @var  string|null */
    protected $message;
    /** @var  mixed */
    protected $value;

    /**
     * ValidationResultItem constructor.
     * @param string           $level
     * @param string|\string[] $path
     * @param string           $message
     * @param mixed            $value
     */
    public function __construct($level, $path, $message, $value) {
        $this->level   = $level;
        $this->path    = $path;
        $this->message = $message;
        $this->value   = $value;
    }

    /**
     * @param string $path
     * @param string|null $message
     * @param mixed $value
     * @return static
     */
    public static function error($path, $message, $value) {
        return new static(self::LEVEL_ERROR, $path, $message, $value);
    }

    /**
     * @param string $path
     * @param string|null $message
     * @param mixed $value
     * @return static
     */
    public static function warning($path, $message, $value) {
        return new static(self::LEVEL_WARNING, $path, $message, $value);
    }

    /**
     * @param string $path
     * @param string|null $message
     * @param mixed $value
     * @return static
     */
    public static function ok($path, $message, $value) {
        return new static(self::LEVEL_OK, $path, $message, $value);
    }

    /**
     * @return bool
     */
    public function isError() {
        return $this->level === self::LEVEL_ERROR;
    }

    /**
     * @return bool
     */
    public function isWarning() {
        return $this->level === self::LEVEL_WARNING;
    }

    /**
     * @return bool
     */
    public function isOk() {
        return $this->level === self::LEVEL_OK;
    }

    /**
     * @return string
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * @return string|\string[]
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}