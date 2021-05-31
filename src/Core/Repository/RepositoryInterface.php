<?php

namespace Lea\Core\Repository;

interface RepositoryInterface
{
    public function save(object $object);
    public static function getById(int $id);
}