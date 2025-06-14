<?php 

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Responses/BaseResponse.php';
require_once __DIR__ . '/../Responses/DataResponse.php';
require_once __DIR__ . '/../Responses/SimpleResponseData.php';
require_once __DIR__ . '/../Enums/HttpStatus.php';
require_once __DIR__ . '/../Helpers/ArrayHelper.php';
require_once __DIR__ . '/../Exceptions/DataValidationException.php';


class UserController extends BaseController {

    /**
     * Fetch a single by id
     * @param integer $id
     * @return void
     */
    public static function get($id = 0) {
        $Response = new DataResponse();
        try {
            $User = User::get($id);
            $User ? 
                $Response->setData($User)->setSuccess('The user was fetched successfully') :
                $Response->setError('The user was not found', HttpStatus::NOT_FOUND);
        } catch (\Throwable $th) {
            $Response->setError($th->getMessage(), HttpStatus::INTERNAL_SERVER_ERROR);
        }
        $Response->output();
    }

    /**
     * Update a user
     * @return void
     */
    public static function update() {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) throw new AuthException();
            $data = self::getReadyData(User::UPDATE_RULES, true);
            $User = AuthService::user();

            if(!empty($data['change_password'])) {
                $data['password'] = AuthService::generatePassword($data['password']);
            }
            unset($data['change_password'], $data['confirm_password']);

            if(!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $User->uploadImage($_FILES['image'], false);
            }
            $data = ArrayHelper::filter($data, array_keys(User::UPDATE_RULES));
            $User->fill($data)->save();
            AuthService::setUserSession($User);
            $Response->setSuccess('The user was updated successfully');
            $Response->setData(['User' => $User->getUserInfo()]);
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        $Response->output();
    }

    /**
     * Create a new user
     * @return void
     */
    public static function create() {
        $Response = new DataResponse();
        try {
            $data = self::getReadyData(User::VALIDATION_RULES);
            $User = (new User())->fill($data)->save();
            $Response->setData((array)$User)->setMessage('The user was created successfully');
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        $Response->output();
    }

    /**
     * Get the profile details of the logged in user
     * @return void
     */
    public static function profile($id = 0) {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) {
                throw new AuthException('You must be logged in to view this page');
            }
            $User = $id ? User::get($id) : AuthService::user();
            $isLoggedUser = AuthService::user()->id === $User->id;
            $Response->setData([
                'user' => $User->getUserInfo($isLoggedUser),
                'rank' => $User->getUserRankDetails()
            ]);
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        $Response->output();
    }

    /**
     * Get the settings of the logged in user
     * @return void
     */
    public static function getSettings() {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) throw new AuthException();
            $Response->setData(AuthService::user()->getSettings()->getPublicData());
            $Response->setMessage('The settings were loaded successfully');
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        $Response->output();
    }

    /**
     * Update the settings of the logged in user
     * @return void
     */
    public static function updateSettings() {
        $Response = new DataResponse();
        try {
            if(AuthService::guest()) throw new AuthException();
            $data = self::getReadyData(Settings::VALIDATION_RULES);
            if(empty($data)) throw new DataValidationException('No data to update');
            $UserSettings = AuthService::user()->getSettings();
            $UserSettings->fill($data)->save();
            $Response->setData($UserSettings->getPublicData());
            $Response->setSuccess('The settings were updated successfully');
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        $Response->output();
        
    }
}
