<?php

declare(strict_types=1);

namespace Lea\Module\ContractorModule\Controller;

use Lea\Core\Controller\Controller;
use Lea\Core\Controller\ControllerInterface;
use Lea\Core\Serializer\Normalizer;
use Lea\Module\ContractorModule\Repository\ContractorRepository;
use Lea\Module\ContractorModule\Service\ContractorService;
use Lea\Response\Response;

class ContractorCollectionController extends Controller implements ControllerInterface
{
    public function init(): void
    {
        $this->repository = new ContractorRepository();

        switch ($this->http_method) {
            
            case "GET":
                $service = new ContractorService($this->repository);
                Response::ok($service->getView());
            case "POST":
                $this->postResource(new ContractorRepository());
                break;
            default:
                Response::methodNotAllowed();
        }
    }
}
