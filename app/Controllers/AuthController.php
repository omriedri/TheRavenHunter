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

    /**
     * Register a new user
     * @return void
     */
    public static function register(): void {
        $Response = new BaseResponse();
        try {
            $data = self::getReadyData(User::REGISTER_RULES);
            if(AuthService::isUserEmailExists($data['email'])) {
                throw new \Exception('Email already in use', 1001);
            }
            if(!empty($data['phone']) && AuthService::isUserPhoneExists($data['phone'])) {
                throw new \Exception('Phone number already in use', 1001);
            }
            $RegisterResponse = AuthService::register($data);
            if(!$RegisterResponse->status) {
                throw new \Exception($RegisterResponse->message, 400);
            }
            $Response->setMessage($RegisterResponse->message);
        } catch (\Throwable $e) {
            $e->getCode() === 1001 ?
                $Response->setMessage($e->getMessage()) :
                $Response->handleException($e);
        }
        self::output($Response);
    }

    /**
     * Send verification email (validation key)
     * @return void
     */
    public static function sendVerifyEmail(): void {
        $Response = new BaseResponse();
        try {
            $data = self::getReadyData(['email' => 'required|email']);
            $ValidationResponse = AuthService::sendVerificationEmail($data['email']);
            $ValidationResponse->status ? 
                $Response->setMessage($ValidationResponse->message) :
                $Response->setError($ValidationResponse->message, 400);
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        self::output($Response);
    }

    /**
     * Verify user email
     * @return void
     */
    public static function verifyEmail() {
        $Response = new BaseResponse();
        try {
            $data = self::getReadyData(['validationKey' => 'required|string|size:32']);
            $VerifyResponse = AuthService::verifyEmail($data['validationKey']);
            $VerifyResponse->status ?
                $Response->setMessage($VerifyResponse->message) :
                $Response->setError($VerifyResponse->message, 400);
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        self::output($Response);
        
    }
}