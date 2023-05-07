<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation;

use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Input\Validation\Attribute\PreValidateResolver;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\ValidatorInterface;

final class ValidatingHydrator implements HydratorInterface
{
    public function __construct(
        private HydratorInterface $hydrator,
        private ValidatorInterface $validator,
        private PreValidateResolver $resolver,
    ) {
    }

    public function hydrate(
        object $model,
        array $data = [],
        array $map = [],
        bool $strict = false
    ): void {
        $result = $this->beforeAction();
        $this->hydrator->hydrate($model, $data, $map, $strict);
        $this->afterAction($model, $result);
    }

    public function create(
        string $class,
        array $data = [],
        array $map = [],
        bool $strict = false
    ): object {
        $result = $this->beforeAction();
        $model = $this->hydrator->create($class, $data, $map, $strict);
        $this->afterAction($model, $result);
        return $model;
    }

    private function beforeAction(): Result
    {
        $result = new Result();
        $this->resolver->setResult($result);
        return $result;
    }

    private function afterAction(object $model, Result $result): void
    {
        if (!$model instanceof ValidatedInputInterface) {
            return;
        }

        $result->isValid()
            ? $this->validator->validate($model)
            : $model->processValidationResult($result);
    }
}
