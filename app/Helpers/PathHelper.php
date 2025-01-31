<?php class PathHelper {

    private const IMAGES_PATH = '/public/images/';
    private const SOUNDS_PATH = '/public/sounds/';
    private const MODULES_PATH = '/public/js/modules/';
    public const  UPLOADS_PATH = '/public/uploads/';

    /**
     * get the image path
     *
     * @param string $name
     * @return string
     */
    public static function image($name): string {
        return self::IMAGES_PATH . $name;
    }

    /**
     * get the sound path
     *
     * @param string $name
     * @return string
     */
    public static function sound($name): string {
        return self::SOUNDS_PATH . $name;
    }

    /**
     * get the module path
     *
     * @param string $name
     * @return string
     */
    public static function module($name): string {
        return self::MODULES_PATH . $name;
    }

}