<?php

namespace api\util;

use JsonSerializable;


class ResponseError implements JsonSerializable {

    private $code;

    private $message;


    public function __construct(int $code, string $message) {
        $this->code = $code;
        $this->message = $message;
    }


    public function getCode(): int {
        return $this->code;
    }


    public function getMessage(): string {
        return $this->message;
    }


    public function setCode(int $code) {
        $this->code = $code;
    }

    public function setMessage(string $message){
        $this->message = $message;
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }
}