<?php
namespace Porabote\Components\Rest;

class RestCurl
{

    private static $protocol = 'https';

    public static function get($params)
    {
        $defaultParams = [
            'url' => '',
            'data' => [],
        ];

        $params = array_merge($defaultParams, $params);

        $httpReferer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;

        $ch = curl_init($params['url'] . '?' . http_build_query($params['data']));
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //  curl_setopt($ch, CURLOPT_USERAGENT, 'sms.php class 1.0 (curl ' . self::$protocol . ')');
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // 5 seconds
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['data']));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $params['token'],
            'ClientId: ' . $params['client_id'],
            'HTTP_REFERER: ' . $httpReferer,
            'SERVER-ADDR: ' . self::getIP(),
        ]);

//print_r($params['data']);
        ob_start();

        curl_exec($ch);
        //$bSuccess = curl_exec($ch);
//        if(curl_exec($ch) === false)
//        {
//            echo 'Curl error: ' . curl_error($ch);
//            return [
//                'curl_error' => curl_error($ch),
//                'code' => 0,
//            ];
//        }

        $response = ob_get_contents();

        ob_end_clean();
        $httpResultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        return [
            'response' => $response,
            'code' => $httpResultCode,
        ];
    }

    public static function post($params)
    {
        $defaultParams = [
            'url' => '',
            'data' => '',
        ];

        $params = array_merge($defaultParams, $params);

        $httpReferer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;

        $ch = curl_init($params['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // do not verify that ssl cert is valid (it is not the case for failover server)
        //  curl_setopt($ch, CURLOPT_USERAGENT, 'sms.php class 1.0 (curl ' . self::$protocol . ')');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['data']));
        curl_setopt($ch, CURLOPT_REFERER, $httpReferer);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $params['token'],
            'ClientId: ' . $params['client_id'],
//            'HTTP-HOST:' . $_SERVER['HTTP_HOST'],
            'HTTP_REFERER: ' . $httpReferer,
//            'REQUEST-URI: ' . $_SERVER["REQUEST_URI"],
//            'SERVER-ADDR: ' . self::getIP(),
        ]);

        ob_start();
        $bSuccess = curl_exec($ch);
        $response = ob_get_contents();

        ob_end_clean();
        $httpResultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'response' => $response,
            'code' => $httpResultCode,
        ];
    }


    public static function postWithFiles($params)
    {
        $defaultParams = [
            'url' => '',
            'data' => '',
        ];

        $data = self::http_build_query_for_curl($params['data']);
        if (isset($params['data']['files']['avatar'])) {
            $data['avatar'] = new \CurlFile($params['data']['files']['avatar']['tmp_name'], 'image/png', 'filename.png');
        }

        $params = array_merge($defaultParams, $params);

        $ch = curl_init($params['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // do not verify that ssl cert is valid (it is not the case for failover server)
        //  curl_setopt($ch, CURLOPT_USERAGENT, 'sms.php class 1.0 (curl ' . self::$protocol . ')');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: multipart/form-data',
        ]);

        ob_start();
        $bSuccess = curl_exec($ch);
        $response = ob_get_contents();

        ob_end_clean();
        $httpResultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'response' => $response,
            'code' => $httpResultCode,
        ];
    }


    static function http_build_query_for_curl($arrays, &$new = array(), $prefix = null)
    {

        if (is_object($arrays)) {
            $arrays = get_object_vars($arrays);
        }

        foreach ($arrays as $key => $value) {
            $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value) or is_object($value)) {
                self::http_build_query_for_curl($value, $new, $k);
            } else {
                $new[$k] = $value;
            }
        }

        return $new;
    }


    private static function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}

?>

