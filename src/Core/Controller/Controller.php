<?php

declare(strict_types=1);

namespace Lea\Core\Controller;

use Lea\Request\Request;
use Lea\Core\Controller\ControllerInterface;
use Lea\Core\Validator\Validator;

abstract class Controller implements ControllerInterface
{
    protected $request;

    function __construct(Request $request, array $params = NULL)
    {
        $this->request = $request;
        $this->params = $params;

        if($params)
            Validator::validateParams($params);
    }
}