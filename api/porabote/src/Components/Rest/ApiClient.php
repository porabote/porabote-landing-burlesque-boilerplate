<?php
namespace Porabote\Components\Rest;

use Porabote\Components\Rest\RestCurl;

//use Rest\Exception;

class ApiClient
{
    public $data = [];
    public $config = [];
    private $host;
    private $clientId;
    private $token;
    private $responseFormat = 'JSON';
    private $url;
    private $response;
    private $responseCode;

    public static function init($config = false)
    {
        $instance = new self();
        $instance->initConfig($config);
       // $instance->initData();

        $instance->host = $config['api_key']['host'];
        $instance->clientId = $config['api_key']['client_id'];
        $instance->token = $config['api_key']['token'];

        return $instance;
    }

    public function setResponseFormat($responseFormat)
    {
        $this->responseFormat = $responseFormat;
        return $this;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function initConfig($config)
    {
        $this->config = $config;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function post()
    {
        $params = [
            'url' => $this->host . $this->url,
            'data' => $this->data,
        ];

        $params['client_id'] = $this->clientId;
        $params['token'] = $this->token;

        $this->response = RestCurl::post($params);
        $this->responseCode = $this->response['code'];

        if ($this->responseFormat == "JSON") {
            return json_decode($this->response['response'], true);
        }

        return $this->response;
    }

    public function postWithFiles()
    {
        $this->response = RestApiCurl::postWithFiles([
            'url' => $this->host . $this->url,
            'data' => $this->data,
        ]);

        $this->responseCode = $this->response['code'];

        if ($this->responseFormat == "JSON") {
            return json_decode($this->response['response'], true);
        }

        return $this->response;
    }

    public function get()
    {
        $params = [
            'url' => $this->host . $this->url,
            'data' => $this->data,
        ];

        $params['client_id'] = $this->clientId;
        $params['token'] = $this->token;

        $this->response = RestApiCurl::get($params);

        $this->responseCode = $this->response['code'];

        if ($this->responseFormat == "JSON") {
            return json_decode($this->response['response'], true);
        }

        return $this->response;
    }


//    public function initData()
//    {
//        $requestData = array_merge($_GET, $_POST);
//
//        if (isset($_SERVER['HTTP_REFERER'])) {
//            $referer = parse_url($_SERVER['HTTP_REFERER']);
//
//            $httpHost = $referer['host'];
//            $requestUri = $referer['path'] . '?' . $referer['query'] . '&' . $_SERVER['QUERY_STRING'];
//        } else {
//            $httpHost = $_SERVER['HTTP_HOST'];
//            $requestUri = $_SERVER['REQUEST_URI'];
//        }
//
//        $data['http_host'] = $httpHost;
//        $data['request_uri'] = str_replace('/api', '', $requestUri);
//
//        $data = array_merge($requestData, $data, $this->config['data_default']);
//
//        $this->data = $data;
//    }



}