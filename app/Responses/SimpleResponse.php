<?php class SimpleResponse {

    protected const DEFAULT_STATUS = true;
    protected const DEFAULT_MESSAGE = '';

    public $status  = self::DEFAULT_STATUS;
    public $message = self::DEFAULT_MESSAGE;

    public function __construct() {
        header('Content-Type: application/json');
    }

    /**
     * Set status
     * @param bool $status
     */
    public function setStatus(bool $status): void {
        $this->status = $status;
    }

    /**
     * Set message
     * @param string $message
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }

    /**
     * Set success message
     * @param string $message
     */
    public function setSuccess(string $message): void {
        $this->setStatus(true);
        $this->setMessage($message);
    }

    /**
     * Set error message
     * @param string $message
     */
    public function setError(string $message): void {
        $this->setStatus(false);
        $this->setMessage($message);
    }
}