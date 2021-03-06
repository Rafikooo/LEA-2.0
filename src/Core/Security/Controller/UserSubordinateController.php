<?php

declare(strict_types=1);

namespace Lea\Core\Security\Controller;

use Lea\Response\Response;
use Lea\Core\Security\Entity\User;
use Lea\Core\Controller\Controller;
use Lea\Core\Serializer\Normalizer;
use Lea\Core\Controller\ControllerInterface;
use Lea\Core\Exception\ResourceNotExistsException;
use Lea\Core\Security\Service\AuthorizedUserService;
use Lea\Core\Security\Service\UserSubordinateService;


class UserSubordinateController extends Controller implements ControllerInterface
{
    public function init(): void
    {
        switch ($this->request->method()) {
            case "GET":
                try {
                    $role_id = AuthorizedUserService::getAuthorizedUserRoleId();
                    $service = new UserSubordinateService;
                    // $objects = $service->findSubordinateUsersRecursive($role_id);
                    $objects = $service->findSubordinateUsersFlat($role_id);
                    $result = Normalizer::denormalizeList($objects);
                    $result = Normalizer::removeSpecificFieldsFromArrayList($result, ['password', 'active', 'deleted', 'token', 'phone', 'mobile_app_token']);
                    Response::ok($result);
                } catch (ResourceNotExistsException $e) {
                    Response::badRequest();
                }
                break;
            default:
                Response::methodNotAllowed();
        }
    }
}
