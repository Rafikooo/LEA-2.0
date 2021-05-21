<?php

namespace Lea\Module\OfferModule\Controller;

use Lea\Core\Controller\ControllerInterface;
use Lea\Request\Request;

class OfferController implements ControllerInterface {

    private $request;
    private $methods;

    function __construct(Request $request, array $params = NULL) {
        $this->request = $request;
        $this->params = $params;
    }

    public function init() {
        die(json_encode($this->params));
    }
}
