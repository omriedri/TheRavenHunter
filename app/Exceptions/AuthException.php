<?php class AuthException extends Exception {

    private const DEFAULT_MESSAGE = "אין הרשאה לביצוע פעולה זו";

    /**
     * AuthException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 401, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}