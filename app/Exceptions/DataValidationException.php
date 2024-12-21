<?php class DataValidationException extends Exception {
    
    private const DEFAULT_MESSAGE = "אחד או יותר מהנתונים שהוזנו אינם תקינים";

    /**
     * DataValidationException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 400, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}