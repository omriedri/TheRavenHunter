<?php class ArrayHelper {

    /**
     * Check if the given array is an associative array
     * @param $arr
     * @return bool
     */
    public static function  isAssociativeArray($arr): bool {
        if(empty($arr)) return false;
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Check if the given value is a valid array
     * @param $value
     * @return bool
     */
    public static function isValidArray($value, $allowEmpty = true): bool {
        if(!is_array($value)) return false;
        if(!$allowEmpty && !count($value)) return false;
        return true;
    }

    /**
     * Check if the given array is an associative array of integers
     * @param array $array
     * @return bool
     */
    public static function isNumericArray($array): bool {
        $nonNumeric = 0;
        if(!is_array($array)) return false;
        if(!count($array)) return false;
        if(self::isAssociativeArray($array)) return false;
        foreach($array as $value) if(!is_numeric($value)) $nonNumeric++;
        return $nonNumeric === 0;
    }

    /**
     * Check if the given array is an associative array of strings
     * @param array $array
     * @return bool
     */
    public static function isStringArray($array): bool {
        $nonStrings = 0;
        if(!is_array($array)) return false;
        if(!count($array)) return false;
        if(self::isAssociativeArray($array)) return false;
        foreach($array as $value) if(!is_string($value)) $nonStrings++;
        return $nonStrings === 0;
    }

    /**
     * Filter out unmatched keys from the given array
     * @param array $array
     * @return array
     */
    public static function filter($data = [], $keys = []): array {
        return array_filter($data, fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY); 
    }
}
