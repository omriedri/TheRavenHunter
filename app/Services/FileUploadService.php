<?php 

require_once __DIR__ . '/../Helpers/PathHelper.php';

class FileUploadService {

    /**
     * Upload an image
     *
     * @param array $file
     * @param string $path
     * @return SimpleResponseData
     */
    public static function uploadImage(array $file, string $path = PathHelper::UPLOADS_PATH): SimpleResponseData {
        $Response = new SimpleResponseData();
        if(!is_dir($path)) mkdir($path, 0777, true);
        $filename = uniqid() . '.jpg';
        $destination = $path . '/' . $filename;
        if(move_uploaded_file($file['tmp_name'], $destination)) {
            $Response->setData(['filename' => $filename]);
        } else {
            $Response->setMessage('שגיאה בהעלאת התמונה');
        }
        return $Response;
    }


    /**
     * Upload, compress & resize the image using tinify
     *
     * @param array $file
     * @param int $width
     * @param int $height
     * @return SimpleResponseData
     */
    public static function uploadCompressUsingTinify(array $file, $width = 250, $height = 250): SimpleResponseData {
        $Response = new SimpleResponseData();
        try {
            $path = PathHelper::UPLOADS_PATH;
            if(!is_dir($path)) mkdir($path, 0777, true);
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = substr("$path$filename", 1);
    
            \Tinify\setKey($_ENV['TINIFY_KEY']);
            $source = \Tinify\fromFile($file['tmp_name']);
            $resized = $source->resize([
                "method" => "cover",
                "width" => $width,
                "height" => $height
            ]);
            $resized->toFile($destination);
            $Response->setData(['filename' => $filename, 'path' => $destination]);
        } catch(\Tinify\AccountException $e) {
            $Response->setMessage('הגעת למגבלת השימוש בחשבון שלך');
        } catch(\Tinify\ClientException $e) {
            $Response->setMessage('תמונה לא תקינה, נסה שנית');
        } catch(\Tinify\ServerException $e) {
            $Response->setMessage('שגיאה בשרת התמונות');
        } catch(\Tinify\ConnectionException $e) {
            $Response->setMessage('שגיאת רשת');
        } catch(Exception $e) {
            $Response->setMessage('שגיאה לא צפויה');
        }
        return $Response;
    }
}
