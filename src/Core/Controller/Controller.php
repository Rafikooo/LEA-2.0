<?php

declare(strict_types=1);

namespace Lea\Core\Controller;

use Lea\Request\Request;
use Lea\Core\Controller\ControllerInterface;
use Lea\Core\SecurityModule\Entity\User;
use Lea\Core\Validator\Validator;
use Lea\Module\Security\Service\TokenVerificationService;

abstract class Controller implements ControllerInterface
{
    protected $request;

    function __construct(Request $request, array $params = NULL, array $allow = NULL)
    {
        $this->request = $request;
        $this->params = $params;
        $this->allow = $allow;

        if($params)
            Validator::validateParams($params);

        if($allow) {
            $auth = new TokenVerificationService();
            $auth->authorize();
            $this->user = new User();
        }
    }
}
