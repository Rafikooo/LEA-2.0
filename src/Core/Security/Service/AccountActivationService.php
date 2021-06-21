<?php

namespace Lea\Module\Security\Service;

use Lea\Core\Service\ServiceInterface;
use Lea\Core\Security\Repository\UserRepository;


final class AccountActivationService extends AuthenticationService implements ServiceInterface
{
    public function activateAccount(string $token, string $password): void
    {
        $user = UserRepository::findByToken($token);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $user->setPassword($hashed_password);
        $user->setActive(true);
        $repository = new UserRepository;
        $repository->save($user);
    }
}
