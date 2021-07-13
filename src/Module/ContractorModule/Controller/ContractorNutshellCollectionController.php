<?php

declare(strict_types=1);

namespace Lea\Module\ContractorModule\Controller;

use Lea\Core\Controller\Controller;
use Lea\Request\Request;
use Lea\Response\Response;
use Lea\Core\Controller\ControllerInterface;
use Lea\Module\ContractorModule\Repository\ContractorRepository;
use Lea\Core\Serializer\Normalizer;
use Lea\Module\ContractorModule\Entity\Contractor;

class ContractorNutshellCollectionController extends Controller implements ControllerInterface
{
    public function init(): void
    {
        switch ($this->request->method()) {
            case "GET":
                $repository = new ContractorRepository;
                $list = $repository->findList();
                $needles = ['fullname', 'employees', 'name', 'surname', 'id'];
                $result = Normalizer::denormalizeSpecificFieldsList($list, $needles);
                // $result = Normalizer::filterSpecificFieldsFromArrayList($result, ['id', 'shortname']);
                // $result = Normalizer::mapKeyOfArrayList();
                Response::ok($result);
            default:
                Response::methodNotAllowed();
        }
    }
}
