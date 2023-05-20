<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator;

use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Hydrator\Validator\Attribute\ValidateResolver;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\ValidatorInterface;

/**
 * ValidatingHydrator is a decorator for {@see HydratorInterface} that validates data before hydration.
 */
final class ValidatingHydrator implements HydratorInterface
{
    /**
     * @param HydratorInterface $hydrator Hydrator to decorate.
     * @param ValidatorInterface $validator Validator to use.
     * @param ValidateResolver $validateResolver Resolver for {@see Validate} attribute.
     */
    public function __construct(
        private HydratorInterface $hydrator,
        private ValidatorInterface $validator,
        private ValidateResolver $validateResolver,
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
        $this->validateResolver->setResult($result);
        return $result;
    }

    private function afterAction(object $object, Result $result): void
    {
        if (!$object instanceof ValidatedInputInterface) {
            return;
        }

        $result->isValid()
            ? $this->validator->validate($object)
            : $object->processValidationResult($result);
    }
}
