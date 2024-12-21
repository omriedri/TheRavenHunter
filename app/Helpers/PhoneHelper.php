<?php require_once __DIR__ . '/StringHelper.php';

class PhoneHelper {

    private const REGEX_IL = "/^((((\+972)|(972)|0)(([234689]\d{7})|([57]\d{8}))|(1[5789]\d{8}))|\*\d{3,6})$/";
    private const REGEX_IL_SHORT = "/^(((0)(([234689]\d{7})|([57]\d{8}))|(1[5789]\d{8}))|\*\d{3,6})$/";

    /**
     * Function validates phone number
     * @param string $phone
     * @param string $country
     * @return boolean
     */
    public static function validate(string $phone = "", $country = 'IL') : bool {
        if(!StringHelper::isValidString($phone)) return false;
        if($country === 'IL') return (bool) preg_match(static::REGEX_IL, $phone);
        else return true;    
    }

    /**
     * Renders phone number to full format
     * @param string $phone
     * @param string $country
     * @return string
     */
    public static function renderToFullFormat($phone = "", $country = 'IL') : string {
        $newPhone = preg_replace("/ /", "", $phone);

        if($country === 'IL' && preg_match(static::REGEX_IL_SHORT, $newPhone)) {
            $newPhone = preg_replace("/^0/", "972", $newPhone);
        }        
        return $newPhone;
    }


    /**
     * Renders phone number to short format
     * @param string $phone
     * @param string $country
     * @return string
     */
    public static function renderToShortFormat($phone = "", $country = 'IL') : string {
        $newPhone = preg_replace("/ |\+|\D+/", "", $phone);

        if($country === 'IL' && preg_match(static::REGEX_IL, $newPhone)) {
            $newPhone = preg_replace("/^972/", "0", $newPhone);
        }        
        return $newPhone;
    }
}