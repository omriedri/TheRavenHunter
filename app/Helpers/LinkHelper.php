<?php class LinkHelper {

    /**
     * Determine if the current request is from localhost
     * @return boolean
     */
    public static function isLocalHost() {
        return $_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1';
    }
    
    /**
     * Function sends cURL http request
     * @param string $url
     * @param string $type GET|POST
     * @param string|array $data
     * @param array $headers
     * @return mixed
     */
    public static function cURL($url, $type, $data, $headers = []){
        try {
            $curl = curl_init();
            $request = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => strtoupper($type),
            ];
    
            if (preg_match("/POST/i", $type)) {
                $request[CURLOPT_POSTFIELDS] = $data;
            }
            if(!empty($headers)) {
                $request[CURLOPT_HTTPHEADER] = $headers;
            }
    
            curl_setopt_array($curl, $request);
    
            if(!$response = curl_exec($curl)) {
                throw new \Exception(curl_error($curl));
            }
        } catch (\Throwable $e ) {
            $response = json_encode([
                'error' => true, 
                'message' => $e->getMessage()
            ], StringHelper::JSON_ENCODE_BEAUTIFY);
        }
        if(isset($curl) && is_resource($curl)) {
            curl_close($curl);
        } elseif(isset($curl)) {
            unset($curl);
        }
        return $response;
    }  
}