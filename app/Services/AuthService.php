<?php

use Aternos\Model\Query\Limit;

require_once __DIR__ . '/EmailService.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/TimeHelper.php';
require_once __DIR__ . '/../Helpers/PhoneHelper.php';
require_once __DIR__ . '/../Exceptions/AuthException.php';
require_once __DIR__ . '/../Enums/HttpStatus.php';
require_once __DIR__ . '/../Responses/BaseResponse.php';
require_once __DIR__ . '/../Responses/SimpleResponse.php';
require_once __DIR__ . '/../Responses/SimpleResponseData.php';

class AuthService {

    /**
     * Login user (create session)
     * @param string $email
     * @param string $password
     * @return SimpleResponseData
     */
    public static function login(string $email, string $password): SimpleResponseData {
        $Response = new SimpleResponseData();
        $User = User::select([
            'email' => $email, 
            'password' => self::generatePassword($password)
        ])[0] ?? null;
        if ($User instanceof User) {
            $User->csrf = self::generateCsrf();
            $User->last_login = date('Y-m-d H:i:s');
            $User->save();
            self::setUserSession($User);
            $Response->setSuccess('You have logged in successfully');
            $Response->data = $User->getUserInfo();
        } else {
            $Response->setError('Invalid email or password', HttpStatus::UNAUTHORIZED);
        }
        return $Response;
    }

    /**
     * Logout user
     * @return SimpleResponse
     */
    public static function logout(): SimpleResponse {
        $Response = new SimpleResponse();
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        if(!isset($_SESSION['User'])) {
            $Response->setError('You are not logged in');
            return $Response;
        }
        unset($_SESSION['User']);
        session_destroy();
        $Response->setSuccess('You have logged out successfully');
        return $Response;
    }

    /**
     * Register a new user
     * @param array $data
     * @return SimpleResponseData
     */
    public static function register(array $data = []): SimpleResponseData {
        $Response = new SimpleResponseData();
        $User = new User();
        $User->id = null;
        $User->first_name = $data['first_name'];
        $User->last_name = $data['last_name'] ?? '';
        $User->gender = $data['gender'] ?? User::GENDER_UNKNOWN;
        $User->email = $data['email'];
        $User->phone = PhoneHelper::renderToFullFormat($data['phone'] ?? '') ?: null;
        $User->password = self::generatePassword($data['password']);
        try {
            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $UploadResponse = FileUploadService::uploadImage($_FILES['image']);
                if($UploadResponse->status) {
                    $User->image = $UploadResponse->data['filename'];
                }
            }
        } catch (\Throwable $e) {
            $User->image = null;
        }
        if(!$User->save()) {
            $Response->setError('Failed to register');
        } else {
            $Response->setSuccess('You have registered successfully');
            $Response->setData($User->getUserInfo());    
        }
        return $Response;
    }

    /**
     * Send verification email
     * @param string $email
     * @return SimpleResponse
     */
    public static function sendVerificationEmail($email = ''): SimpleResponse {
        $Response = new SimpleResponse();
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $Response->setError('Invalid email');
            return $Response;
        }
        if(!self::isUserEmailExists($email)) {
            $Response->setError('Email not found');
            return $Response;
        }
        $User = User::select(['email' => $email], null, null, 1)[0];
        $verification_key = bin2hex(random_bytes(6));
        $subject = 'Verification Code';
        $message = self::getVerficationEmailHTML($verification_key, $User->first_name);
        if(EmailService::sendNoReplayEmail($email, $subject, $message)) {
            $_SESSION['verification_code'] = $verification_key;
            $Response->setSuccess('Verification key has been sent to your email');
        } else {
            $Response->setError('Failed to send Verification key');
        }
        return $Response;
    }

    /**
     * Verify user email
     * @param string $verification_code
     * @return SimpleResponse
     */
    public static function verifyEmail(string $verification_code = '') {
        $Response = new SimpleResponse();
        if(empty($key)) {
            $Response->setError('Invalid verification code');
            return $Response;
        }
        if($verification_code === $_SESSION['verification_code'] ?? '') {
            $Response->setSuccess('Email has been verified');
        } else {
            $Response->setError('Invalid verification code');
        }
        return $Response;
    }

    /**
     * Get verification email HTML
     * @param string $key
     * @param string $name
     * @return string
     */
    private static function getVerficationEmailHTML($code, $name) {
        $compayName = $_ENV['COMPANY_NAME'] ?? 'The Raven Hunter';
        return '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                    }
                    .container {
                        width: 80%;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        background-color: #f9f9f9;
                    }
                    .header {
                        text-align: center;
                        padding-bottom: 20px;
                    }
                    .content {
                        padding: 20px;
                    }
                    .footer {
                        text-align: center;
                        padding-top: 20px;
                        font-size: 0.9em;
                        color: #777;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Vefication Code</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $name . ',</p>
                        <p>Your verification code is: <strong>' . $code . '</strong></p>
                        <p>Please use this code to in the form to reset your password.</p>
                        <p>Thank you!</p>
                    </div>
                    <div class="footer">
                        <p>&copy;' . date('Y') . ' '. $compayName . ', All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ';
    }

    /**
     * Reset user password
     * @param string $email
     * @param string $password
     * @return SimpleResponse
     */
    public static function resetPassword(string $email, string $password): SimpleResponse {
        $Response = new SimpleResponse();
        if(!self::isUserEmailExists($email)) {
            $Response->setError('Email not found');
            return $Response;
        }
        $User = User::select(['email' => $email], null, null, 1)[0];
        $User->password = self::generatePassword($password);
        if(!$User->save()) {
            $Response->setError('Failed to reset password');
        } else {
            $Response->setSuccess('Password has been reset successfully');
        }
        return $Response;
    }

    /**
     * Get logged in user instance
     * @return User|null
     */
    public static function user(): ?User {
        return isset($_SESSION['User']) ? unserialize($_SESSION['User']) : null;
    }

    /**
     * Check if user is logged in and not guest
     * @return boolean
     */
    public static function check(): bool {
        return !empty($_SESSION['User']);
    }

    /**
     * Check if user is guest and not logged in
     * @return boolean
     */
    public static function guest(): bool {
        return !self::check();
    }

    /**
     * Generate CSRF token
     * @return string
     */
    private static function generateCsrf(): string {
        return bin2hex(random_bytes(32));
    }

    /**
     * Generate password hash
     * @param string $password
     * @return string
     */
    public static function generatePassword(string $password): string {
        return hash('sha256', $_ENV['PASSWORD_SALT'] . "#$password#");
    }

    /**
     * Set user session
     * @param User $User
     * @return boolean
     */
    public static function setUserSession(User $User): bool {
        return (bool) $_SESSION['User'] = serialize($User);
    }

    /**
     * Check if user email exists
     * @param string $email
     * @return boolean
     */
    public static function isUserEmailExists(string $email): bool {
        return User::select(['email' => $email], null, null, new Limit(1))->count() > 0;
    }

    public static function isUserPhoneExists(string $phone): bool {
        return User::select(['phone' => PhoneHelper::renderToFullFormat($phone)], null, null, new Limit(1))->count() > 0;
    }
}