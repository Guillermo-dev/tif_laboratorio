<?php

namespace api\exceptions;

use Exception;
use Throwable;

/**
 * Class ApiException
 *
 * @package api\exceptions
 */
class ApiException extends Exception {
    /**
     * ApiException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}