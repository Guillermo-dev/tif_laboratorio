<?php

namespace api\util;

use JsonException;
use JsonSerializable;

class Response implements JsonSerializable {
    private $status;

    private $data;

    private $error;

    private static $response = null;

    private function __construct() {
        $this->status = 'success';
        $this->data = null;
        $this->error = null;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getData(): ?array {
        return $this->data;
    }


    public function getError(): ?ResponseError {
        return $this->error;
    }

    public function setStatus(string $status) {
        $this->status = $status;
    }

    public function setData(?array $data) {
        $this->data = $data;
    }


    public function setError(string $error) {
        $this->error = new ResponseError($error);
    }


    public function appendData(string $key, $value) {
        if ($this->data === null)
            $this->data = [];
        $this->data[$key] = $value;
    }

    public function send(): void {
        header('Content-type: application/json', true);
        try {
            echo json_encode(get_object_vars($this), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->setData(null);
            $this->setError($e->getMessage());
            echo json_encode(get_object_vars($this));
        }
    }


    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    public static function getResponse(): Response {
        if (!self::$response)
            self::$response = new Response();
        return self::$response;
    }
}
