<?php 

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Responses/BaseResponse.php';
require_once __DIR__ . '/../Responses/DataResponse.php';
require_once __DIR__ . '/../Helpers/ArrayHelper.php';
require_once __DIR__ . '/../Enums/HttpStatus.php';
require_once __DIR__ . '/../Exceptions/DataValidationException.php';
require_once __DIR__ . '/../Models/User.php';


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
                $Response->setData($User)->setSuccess('User fetched successfully') : 
                $Response->setError('User not found', HttpStatus::NOT_FOUND);
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
        $Response = new BaseResponse();
        try {
            $rules = array_merge(User::VALIDATION_RULES, ['id' => 'required|integer']);
            $data = self::getReadyData($rules, true);
            $User = User::get($data['id']);
            if(!$User) throw new DataValidationException('User not found');
            $User->fill($data)->save();
            $Response->setSuccess('User updated successfully');
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
            $Response->setData((array)$User)->setMessage('User created successfully');
        } catch (\Throwable $e) {
            $Response->handleException($e);
        }
        $Response->output();
    }
}
