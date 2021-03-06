<?php

namespace Lea\Core\Entity;

use Lea\Core\Reflection\ReflectionClass;
use Lea\Core\Reflection\ReflectionProperty;
use Lea\Core\Security\Service\AuthorizedUserService;
use Lea\Core\Validator\AnnotationValidator;
use Lea\Core\Validator\TypeValidator;
use TypeError;

trait EntitySetter
{
    use Parser;

    public function set(array $data): void
    {
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            $key = $property->getName();
            if (!array_key_exists($key, $data) && $key != 'user_id')
                continue;
            $setValue = 'set' . $this->processSnakeToPascal($key);
            if ($property->isObject()) {
                if (is_iterable($data[$key])) {
                    $children = [];
                    foreach ($data[$key] as $obj) {
                        $ChildClass = $property->getType2();
                        /* Disposable - begin */
                        if (str_contains($ChildClass, "File") && !isset($obj['id']) && !isset($_FILES[$obj['file_key']]))
                            continue;
                        /* Disposable - end */
                        $children[] = new $ChildClass($obj);
                    }
                    $this->$setValue($children);
                }
            } else {
                if ($setValue == 'setUserId' && !isset($data[$key]))
                    $val = AuthorizedUserService::getAuthorizedUserId();
                else
                    $val = TypeValidator::getTypedValue($data[$key], $property, TypeValidator::CLIENT);

                $this->strictSet($setValue, $val, $property);
            }
        }
    }

    private function strictSet(string $setValue, $value, ReflectionProperty $property): void
    {
        // if($property->getType2() != gettype($value) && (!($property->getName() != 'id' || $property->getName() != 'active' || $property->getName() != 'deleted')))
        // throw new TypeError($property->getName());
        try {
            $this->$setValue($value);
        } catch (TypeError $e) {
            $type = $property->getType2();
            throw new TypeError($setValue . " - expected $type, got $value");
        }
    }

    public function getSetters(): array
    {
        foreach (get_class_methods($this) as $method) {
            if (AnnotationValidator::hasPropertyCorrespondingToMethod($this, $method, true))
                $setters[] = $method;
        }

        return $setters ?? [];
    }
}
