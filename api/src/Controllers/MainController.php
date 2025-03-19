<?php

namespace App\Controllers;

use Porabote\Components\Rest\ApiClient;
use Porabote\Components\Recaptcha\Recaptcha;

class MainController
{

    public $request;
    public $response;
    public $configs;

    public function sendData()
    {
        $res = Recaptcha::check($this->request->input->get('captcha_token_v3'), null);
        if (isset($res['error'])) {
            return $this->response->json($res);
        }

        $phone = $this->request->input->get('Phone');

        $data = [
            'api' => 'ec3459df2701beb8334357d9ba7f283d',
            'type' => 1929,
            'ip' => $this->getIP(),
            'phone' => $phone,
            //  'name' => 'Test Name',
            // 'club' => 'msk',
            'http_host' => $_SERVER['HTTP_HOST'],
            'request_uri' => $_SERVER['REQUEST_URI'],
            'getResult' => true,
        ];

        ApiClient::init($this->configs)
            ->setHost('https://bvcrm.ru')
            ->setUrl('/crm/add.php')
            ->setResponseFormat('HTTP')
            ->setData($data)
            ->post();

        // $data = array_merge($this->request->all(), [
        //     'handler' => 'opros2025',
        //     'action' => 'writeToGoogleTable',
        //     'type' => 1,
        // ]);


        // $resp = ApiClient::init($this->configs)
        //     ->setHost('https://cp.passmen.club/api')
        //     ->setUrl('/landings/action/handleData')
        //     ->setResponseFormat('HTTP')
        //     ->setData($data)
        //     ->post();

        return $this->response->json([
            'result' => 'OK', // TODO error
        ]);
    }

    public function isIssetCyrillic($string)
    {
        $string = preg_replace("/[\s0-9a-z,]/iu", '', $string);
        if (strlen($string) == 0) {
            exit();
        }
    }

    private function getIP() {
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
