<?php require_once __DIR__ . '/PathHelper.php';

class ImageHelper {

    /**
     * get the image path
     * 
     * @return string 
     */
    public static function get($name): string {
        return PathHelper::image($name);
    }
}