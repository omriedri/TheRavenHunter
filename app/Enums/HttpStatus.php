<?php class HttpStatus {

    public $code = self::OK;
    public $message = "OK";
    public $description = "The request has succeeded.";

    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const BAD_ENTITY = 422;

    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;

    public function __construct(int $code = 200) {
        $this->code = $code;
        $this->message = self::getMessage($code);
        $this->description = self::getErrorDescription($code);
    }

    public static function getMessage($code = 200) {
        switch ($code) {
            case 200: return "OK";
            case 201: return "Created";
            case 202: return "Accepted";
            case 204: return "No Content";
            case 400: return "Bad Request";
            case 401: return "Unauthorized";
            case 403: return "Forbidden";
            case 404: return "Not Found";
            case 500: return "Internal Server Error";
            case 501: return "Not Implemented";
            case 502: return "Bad Gateway";
            default: return "Unknown";
        }
    }

    public static function getErrorDescription($code = 200) {
        switch ($code) {
            case 200: return "The request has succeeded.";
            case 201: return "The request has succeeded and a new resource has been created as a result of it.";
            case 202: return "The request has been accepted for processing, but the processing has not been completed.";
            case 204: return "The request has succeeded but returns no message body.";
            case 400: return "The server cannot process the request due to a client error.";
            case 401: return "The client must authenticate itself to get the requested response.";
            case 403: return "The client does not have access rights to the content.";
            case 404: return "The resource could not be found.";
            case 500: return "A generic error message, given when an unexpected condition was encountered.";
            case 501: return "The server either does not recognize the request method, or it lacks the ability to fulfill the request.";
            case 502: return "The server was acting as a gateway or proxy and received an invalid response from the upstream server.";
            default: return "Unknown";
        }
    }
}