<?php

namespace Lea\Module\HomepageModule\Controller;

use Lea\Core\Controller\Controller;
use Lea\Core\Controller\ControllerInterface;
use Lea\Request\Request;
use Lea\Response\Response;

class HomepageController extends Controller implements ControllerInterface
{
    public function init(): void
    {
        switch ($this->request->method()) {
            case "GET":
                header("Content-Type: text/html; charset=UTF-8");
                $response = "<center><table style='padding: 20px; border: 1px solid green; display: table-cell; vertical-align: center;'><tr><td>API</td><td>LEA-2.0</td><td>DZIAŁA</td></tr></table></center>";
                die($response);
            default:
                Response::notFound();
        }
    }
}
