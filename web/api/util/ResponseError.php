<?php

namespace api\util;

use JsonSerializable;

class ResponseError implements JsonSerializable {

    private $error;

    public function __construct(string $error) {
        $this->error = $error;
    }


    public function getError(): string {
        return $this->error;
    }

    public function setError(string $error) {
        $this->error = $error;
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }
}