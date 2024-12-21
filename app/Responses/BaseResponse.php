<?php 
require_once __DIR__ . '/../Enums/HttpStatus.php';
require_once __DIR__ . '/../Helpers/StringHelper.php';

class BaseResponse {
    
    public int $status                      = self::DEFAULT_STATUS;
    public bool $success                    = self::DEFAULT_SUCCESS;
    public string $message                  = self::DEFAULT_MESSAGE;

    protected const DEFAULT_SUCCESS         = true;
    protected const DEFAULT_STATUS          = HttpStatus::OK;
    protected const DEFAULT_MESSAGE         = "הפעולה בוצעה בהצלחה";
    protected const DEFAULT_ERROR_MESSAGE   = "הפעולה נכשלה";

    /**
     * BaseResponse constructor.
     */
    public function __construct(){
        $this->status = HttpStatus::OK;
    }

    /**
     * Set custom message
     *
     * @param string $message
     * @return BaseResponse
     */
    public function setMessage(string $message):BaseResponse {
        $this->message = $message;
        return $this;
    }

    /**
     * Set success status and message
     *
     * @param string|null $message
     * @return BaseResponse
     */
    public function setSuccess(?string $message = null): BaseResponse {
        $this->success = true;
        $this->message = $message ?? $this->message;
        return $this;
    }

    /**
     * Set error message
     *
     * @param string $message
     * @param int $status
     * @return BaseResponse
     */
    public function setError(string $message = '', int $status = HttpStatus::BAD_REQUEST): BaseResponse {
        $this->success = false;
        $this->status = $status;
        $this->message = empty($message) ? self::DEFAULT_ERROR_MESSAGE : $message;
        return $this;
    }

    /**
     * Output (echo) the response
     */
    public function output(): void {
        http_response_code($this->status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this, StringHelper::JSON_ENCODE_BEAUTIFY);
    }

    /**
     * Handle exception
     *
     * @param \Throwable $Exception
     */
    public function handleException(\Throwable $Exception): void {
        if($Exception instanceof DataValidationException) {
            $this->setError($Exception->getMessage(), HttpStatus::BAD_REQUEST);
        } else {
            $this->setError($Exception->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}