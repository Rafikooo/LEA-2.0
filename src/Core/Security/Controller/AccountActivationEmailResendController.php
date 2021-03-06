<?php

namespace Lea\Core\Security\Controller;

use Lea\Response\Response;
use Lea\Core\Validator\Validator;
use Lea\Core\Controller\Controller;
use Lea\Core\Controller\ControllerInterface;
use Lea\Core\Exception\EmailNotSentException;
use Lea\Core\Exception\ResourceNotExistsException;
use Lea\Core\Security\Service\AccountActivationEmailResendService;

class AccountActivationEmailResendController extends Controller implements ControllerInterface
{
    public function init(): void
    {
        switch ($this->request->method()) {
            case "GET":
                $email = urldecode($this->params['email']);
                Validator::validateEmail($email);
                $service = new AccountActivationEmailResendService;
                try {
                    $service->resendEmail($email);
                } catch (ResourceNotExistsException $e) {
                    Response::badRequest("Invalid Token");
                } catch (EmailNotSentException $e) {
                    Response::accepted("Caution! Email not sent, but 202 status code"); /* Workaround */
                }
                Response::accepted();
            default:
                Response::methodNotAllowed();
        }
    }
}
