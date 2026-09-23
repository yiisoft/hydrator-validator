<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator;

use ReflectionClass;
use Yiisoft\Hydrator\DataInterface;
use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\Attribute\ValidateResolver;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\ValidatorInterface;

/**
 * `ValidatingHydrator` is a decorator for {@see HydratorInterface}:
 *
 * - it allows to validate raw data of properties marked with {@see Validate} PHP attribute before passing it to the
 * decorated hydrator;
 * - it allows to validate object after creating or populating it.
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
    ) {}

    public function hydrate(object $object, array|DataInterface $data = []): void
    {
        $result = $this->beforeAction();

        try {
            $this->validateClass($object::class, $data, $result);
            $this->hydrator->hydrate($object, $data);
            $this->afterAction($object, $result);
        } finally {
            $this->validateResolver->setResult(null);
        }
    }

    public function create(string $class, array|DataInterface $data = []): object
    {
        $result = $this->beforeAction();

        try {
            $this->validateClass($class, $data, $result);
            $object = $this->hydrator->create($class, $data);
            $this->afterAction($object, $result);
            return $object;
        } finally {
            $this->validateResolver->setResult(null);
        }
    }

    private function beforeAction(): Result
    {
        $result = new Result();
        $this->validateResolver->setResult($result);
        return $result;
    }

    private function validateClass(string $class, array|DataInterface $data, Result $result): void
    {
        if (!class_exists($class)) {
            return;
        }

        /** @var class-string $class */
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getAttributes(Validate::class) as $reflectionAttribute) {
            $validationResult = $this->validator->validate($data, $reflectionAttribute->newInstance()->getRules());

            foreach ($validationResult->getErrors() as $error) {
                $result->addError(
                    $error->getMessage(),
                    $error->getParameters(),
                    $error->getValuePath(),
                );
            }
        }
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
