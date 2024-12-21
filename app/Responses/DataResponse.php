<?php require_once __DIR__ . '/BaseResponse.php';

class DataResponse extends BaseResponse {

    public int $status          = self::DEFAULT_STATUS;
    public bool $success        = self::DEFAULT_SUCCESS;
    public string $message      = self::DEFAULT_MESSAGE;
    public array $data          = [];

    /**
     * Set data to the response
     *
     * @param array $data
     * @param string|null $message
     * @return DataResponse
     */
    public function setData($data = [], $message = null):DataResponse {
        $this->message = $message ?? $this->message;
        $this->data = (array) $data;
        return $this;
    }

}