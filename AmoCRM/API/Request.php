<?php
namespace AmoCRM\API;

class Request {
    /**
     * Request class
     * Implements HTTP requests using CURL
     */

    /**
     * Get preconfigured CURL instance
     * 
     * @param string $url Address URL
     * @param string $method Request method
     * @param array $headers Additional request headers
     */
    private function initCurl($url, $method = "GET", $headers = []) {
        array_push($headers, "Content-Type: application/json");

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-oAuth-client/1.0");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        return $curl;
    }

    /**
     * Make GET request
     * 
     * @param string $url Request URL
     * @param array $headers Additional headers
     */
    function get($url, $headers = []) {
        $curl = $this->initCurl($url, "GET", $headers);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $this->handleResponseCode((int)$code, $out);

        return json_decode($out, true);
    }

    /**
     * Make POST request
     * 
     * @param string $url Request URL
     * @param array $data Request body data
     * @param array $headers Additional headers
     */
    function post($url, $data = [], $headers= []) {
        $curl = $this->initCurl($url, "POST", $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $this->handleResponseCode((int)$code, $out);
        
        return json_decode($out, true);
    }

    /**
     * Make POST request
     * 
     * @param string $url Request URL
     * @param array $data Request body data
     * @param array $headers Additional headers
     */
    function patch($url, $data = [], $headers = []) {
        $curl = $this->initCurl($url, "PATCH", $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $this->handleResponseCode((int)$code, $out);
        
        return json_decode($out, true);
    }

    /**
     * Handle response code and throw exception if something's wrong
     * @throws Exception
     */
    private function handleResponseCode($code, $out) {
        $errors = [
            400 => "Bad request",
            401 => "Unauthorized",
            403 => "Forbidden",
            404 => "Not found",
            500 => "Internal server error",
            502 => "Bad gateway",
            503 => "Service unavailable",
        ];

        try {
            if ($code < 200 || $code > 204) {
                throw new \Exception(isset($errors[$code]) ? $errors[$code] : "Undefined error", $code);
            }
        }
        catch(\Exception $e) {
            die("Error: " . $e->getMessage() . " (" . $e->getCode() . ")" . PHP_EOL . $out);
        }
    }
}
