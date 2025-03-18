<?php
namespace App;

use App\Controllers\MainController;
use Porabote\Core\Request;
use Porabote\Core\Response;

class Application
{

    public $configs;
    public $request;

    public function __construct($configs)
    {
        $this->configs = $configs;
    }

    public function run()
    {
        try {
            //header('Content-Type: application/json; charset=utf-8');

            $controller = new MainController();

            $controller->response = new Response();

            $controller->request = new Request();
            $controller->configs = $this->configs;

            $controller->{$controller->request->input->get('action')}($this);

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