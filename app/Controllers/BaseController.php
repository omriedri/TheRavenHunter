<?php

use Rakit\Validation\Validator;

class BaseController {

    /**
     * Render a view
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    protected static function view($view, $data = []): void {
        extract($data);
        require_once BASE_PATH . "/app/Views/$view.php";
    }

    /**
     * Output data as JSON
     *
     * @param mixed $data
     * @return void
     */
    protected static function output($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Get incoming data, filter, validate and return ready data
     *
     * @param array $rules
     * @param boolean $isUpdate
     * @return array
     * @throws DataValidationException
     */
    protected static function getReadyData(array $rules, bool $isUpdate = false): array {
        $data = self::filter(self::getIncomingData(), array_keys($rules));
        self::validate($data, $rules, $isUpdate);
        return $data;
    }

    /**
     * Get incoming data from request
     *
     * @return array
     */
    protected static function getIncomingData(): array {
        return (array) ($_REQUEST ?: json_decode(file_get_contents('php://input'), true));
    }

    /**
     * Filter incoming data
     *
     * @param array $data
     * @param array $keys
     * @return array
     */
    protected static function filter(array $data, array $keys): array {
        return array_filter($data, fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Validate incoming data
     *
     * @param array $data
     * @param array $rules
     * @param boolean $isUpdate
     * @return array
     * @throws DataValidationException
     */
    protected static function validate(array $data, array $rules, bool $isUpdate = false): array {
        if(empty($data)) {
            throw new DataValidationException('לא התקבלו נתונים');
        }
        if(!ArrayHelper::isAssociativeArray($data)) {
            throw new DataValidationException('מבנה הנתונים שהתקבלו אינו תקין');
        }
        if($isUpdate) {
            $rules = array_map(fn($rule) => str_replace('required|', '', $rule), $rules);
        }
        $validator = new Validator;
        $validation = $validator->make($data, $rules);
        $validation->validate();
        if ($validation->fails()) {
            $errorMessage = array_values($validation->errors()->firstOfAll())[0];
            throw new DataValidationException($errorMessage);
        }
        return $validation->getValidatedData();
    }
}