<?php

class StringHelper {

    public const JSON_ENCODE_BEAUTIFY = JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT;


    /**
     * Function to clean string from any tags and special characters
     * @param string $string
     * @return string
     */
    public static function cleanSpecialChars(string $string): string {
        return preg_replace('/[^A-Za-z0-9\-א-ת]/', '', $string);
    }

    /**
     * Function validate if given value is valid string
     * @param mixed $value
     * @return boolean
     */
    public static function isValidString($value) {
        return !empty($value) && is_string($value);
    }


    /**
     * Function validate if given value is valid email
     * @param string $email
     * @return boolean
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if given string is valid json
     * @param string $string
     * @return boolean
     */
    public static function isJson($string) {
        self::isValidString($string);
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Function to check if given string is a valid URL
     * @param string $url
     * @return boolean
     */
    public static function isContainsHTML($string) {
        return preg_match('/<[^>]*>/', $string);
    }    

    /**
     * Function returns a mixed characters & digits string
     * @param integer $offset
     * @param integer $length
     * @return string
     */
    public static function getShuffledString(int $offset = 0, int $length = 6): string {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), $offset, $length);
    }

    /**
     * Function to convert a numeric string from number format to int
     * @param string $numberString
     * @return int|false
     */
    public static function convertNumberStringToInt($numberString) {
        return (int) str_replace(',', '', $numberString);
    }

}