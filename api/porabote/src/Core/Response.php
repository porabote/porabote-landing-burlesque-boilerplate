<?php
namespace Porabote\Core;

class Response {

    public function json($data)
    {
        echo json_encode($data);
        exit();
    }
}