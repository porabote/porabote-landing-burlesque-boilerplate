<?php
namespace Porabote\Core;

class Router
{

    public static function run()
    {
        try {

        } catch (Exception $e) {
            self::setResponse(['error' => $e->getMessage()]);
        }
    }

    private static function setResponse($data)
    {
        echo json_encode($data);
        exit();
    }

}