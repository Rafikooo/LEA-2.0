<?php

declare(strict_types=1);

namespace Lea\Module\ContractorModule\Controller;

use Lea\Response\Response;
use Lea\Core\Controller\Controller;
use Lea\Core\Serializer\Normalizer;
use Lea\Core\Controller\ControllerInterface;
use Lea\Core\Exception\ResourceNotExistsException;
use Lea\Module\ContractorModule\Entity\LibraryAdministrativeDivision;
use Lea\Module\ContractorModule\Repository\LibraryAdministrativeDivisionRepository;


class LibraryAdministrativeDivisionController extends Controller implements ControllerInterface
{
    public function init()
    {
        switch ($this->request->method()) {
            case "GET":
                try {
                    $repository = new LibraryAdministrativeDivisionRepository($this->params);
                    $object = $repository->getByField(new LibraryAdministrativeDivision, 'postcode', $this->params['postcode']);
                    $res = Normalizer::denormalize($object);
                    Response::ok($res);
                } catch (ResourceNotExistsException $e) {
                    Response::badRequest("");
                }
                break;
            default:
                Response::methodNotAllowed();
        }
    }
}
