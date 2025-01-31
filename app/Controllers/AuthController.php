<?php 

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Responses/BaseResponse.php';
require_once __DIR__ . '/../Responses/DataResponse.php';

class AuthController extends BaseController {

    /**
     * Login user (create session)
     * @return void
     */
    public static function login(): void {
        $Response = new DataResponse();
        try {
            $data = self::getReadyData(User::AUTH_RULES);
            $AuthResponse = AuthService::login($data['email'], $data['password']);
            if($AuthResponse->status) {
                $Response->setSuccess($AuthResponse->message);
                $Response->setData($AuthResponse->data);
            } else {
                $Response->setError($AuthResponse->message, HttpStatus::UNAUTHORIZED);
            }
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        self::output($Response);
    }

    /**
     * Logout user (destroy session)
     * @return void
     */
    public static function logout(): void {
        $Response = new BaseResponse();
        try {
            $AuthResponse = AuthService::logout();
            $AuthResponse->status ? 
                $Response->setSuccess($AuthResponse->message) :
                $Response->setError($AuthResponse->message);
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        self::output($Response);
    }


    /**
     * Check if user is logged in
     * @return void
     */
    public static function check(): void {
        $Response = new DataResponse();
        try {
            $isLogged = AuthService::check();
            $Response->setMessage($isLogged ? 'Logged in' : 'Not logged in');
            $data = ['isLogged' => $isLogged];
            if($isLogged) {
                $data['User'] = AuthService::user()->getUserInfo();
            }
            $Response->setData($data);
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        self::output($Response);
    }
}