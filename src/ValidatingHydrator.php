<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation;

use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Input\Validation\Attribute\EarlyValidationResolver;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\ValidatorInterface;

final class ValidatingHydrator implements HydratorInterface
{
    public function __construct(
        private readonly HydratorInterface $hydrator,
        private readonly ValidatorInterface $validator,
        private readonly EarlyValidationResolver $earlyValidationResolver,
    ) {
    }

    public function hydrate(
        object $object,
        array $data = [],
        array $map = [],
        bool $strict = false
    ): void {
        $result = $this->beforeAction();
        $this->hydrator->hydrate($object, $data, $map, $strict);
        $this->afterAction($object, $result);
    }

    public function create(
        string $class,
        array $data = [],
        array $map = [],
        bool $strict = false
    ): object {
        $result = $this->beforeAction();
        $object = $this->hydrator->create($class, $data, $map, $strict);
        $this->afterAction($object, $result);
        return $object;
    }

    private function beforeAction(): Result
    {
        $result = new Result();
        $this->earlyValidationResolver->setResult($result);
        return $result;
    }

    private function afterAction(object $object, Result $result): void
    {
        if (!$object instanceof ValidatedObjectInterface) {
            return;
        }

        $result->isValid()
            ? $this->validator->validate($object)
            : $object->processValidationResult($result);
    }
}
