<?php
namespace Porabote\Core;

use Porabote\Components\DataReader\DataReader;

class Request {

    public $input;

    public function __construct()
    {
        $params = explode("&", $_SERVER['QUERY_STRING']);

        foreach ($params as $param) {
            list($name, $value) = explode('=', $param);
            $this->input[$name] = urldecode($value);
        }

        $this->input = new DataReader(array_merge($this->input, $_POST));
    }

    public function all()
    {
        return $this->input->toArray();
    }

}