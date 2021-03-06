<?php

declare(strict_types=1);

namespace Lea\Module\CalendarModule\Controller;

use Lea\Response\Response;
use Lea\Core\Controller\Controller;
use Lea\Core\Serializer\Normalizer;
use Lea\Core\Controller\ControllerInterface;
use Lea\Module\CalendarModule\Repository\CalendarEventRepository;

class MonthlyScheduleController extends Controller implements ControllerInterface
{
    public function init(): void
    {
        switch ($this->request->method()) {
            case "GET":
                $repository = new CalendarEventRepository;
                $list = $repository->findCalendarEventListByConstraints($this->params['month'], $this->params['year'], (int)$this->params['user_id'] ?? null);
                $res = Normalizer::denormalizeList($list);
                Response::ok($res);
                break;
            default:
                Response::methodNotAllowed();
        }
    }
}
