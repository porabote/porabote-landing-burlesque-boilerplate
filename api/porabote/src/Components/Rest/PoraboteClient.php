<?php

namespace components\Curl;

use Rest\Exception;

class PoraboteClient
{
    public $data = [];
    public $config = [];
    public $displayErrors = false;

    public function __construct($config = false)
    {
        $this->initConfig($config);
        $this->initData();
    }

    public function initConfig($config)
    {
        $this->displayErrors = $config['displayErrors'];

        if ($this->displayErrors) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        }

        $this->config = $config;
    }

    public function initData()
    {
        $requestData = array_merge($_GET, $_POST);

        $data = [
            'phone' => isset($requestData['phone']) ? $requestData['phone'] : null,
            'name' => isset($requestData['name']) ? $requestData['name'] : null,
        ];

        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = parse_url($_SERVER['HTTP_REFERER']);

            $httpHost = $referer['host'];
            $requestUri = $referer['path'] . '?' . $referer['query'] . '&' . $_SERVER['QUERY_STRING'];
        } else {
            $httpHost = $_SERVER['HTTP_HOST'];
            $requestUri = $_SERVER['REQUEST_URI'];
        }

        $data['http_host'] = $httpHost;
        $data['request_uri'] = str_replace('/api', '', $requestUri);

        $data = array_merge($requestData, $data, $this->config['data_default']);

        $this->data = $data;
    }

    public function send()
    {
        try {
            $resp = ApiClient::init(
                $this->config['api_key']['host'],
                $this->config['api_key']['client_id'],
                $this->config['api_key']['token']
            )
                ->setUrl('/leads/action/receive')
                ->setData($this->data)
                ->post();

            if (isset($resp['error'])) {
                echo json_encode(['error' => $resp['error']]);
                exit();
            }

            if (!isset($resp['data'])) {
                echo json_encode(['error' => 'response error']);
                exit();
            }

            echo '<pre>';
            print_r($resp['data']);

            echo json_encode($resp['data']);

        } catch (Exception $e) {

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'error' => $e->getMessage()
            ]);
            exit();
        }

    }

}