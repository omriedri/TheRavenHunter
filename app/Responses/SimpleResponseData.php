<?php require_once __DIR__ . '/SimpleResponse.php';

class SimpleResponseData extends SimpleResponse {

    public $status = self::DEFAULT_STATUS;
    public $message = self::DEFAULT_MESSAGE;
    public $data = [];

    public function setData($data = [], $message = null): SimpleResponseData {
        $this->message = $message ?? $this->message;
        $this->data = (array) $data;
        return $this;
    }

}