<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Attribute;

use Yiisoft\Hydrator\Context;
use Yiisoft\Hydrator\NotResolvedException;
use Yiisoft\Hydrator\ParameterAttributeInterface;
use Yiisoft\Hydrator\ParameterAttributeResolverInterface;
use Yiisoft\Hydrator\UnexpectedAttributeException;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\ValidatorInterface;

final class EarlyValidationResolver implements ParameterAttributeResolverInterface
{
    private ?Result $result = null;

    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function setResult(?Result $result): void
    {
        $this->result = $result;
    }

    public function getParameterValue(ParameterAttributeInterface $attribute, Context $context): mixed
    {
        if (!$attribute instanceof EarlyValidation) {
            throw new UnexpectedAttributeException(EarlyValidation::class, $attribute);
        }

        if ($this->result !== null) {
            $parameterName = $context->getParameter()->getName();
            $result = $this->validator->validate(
                $context->isResolved() ? [$parameterName => $context->getResolvedValue()] : [],
                [$parameterName => $attribute->getRules()],
            );

            foreach ($result->getErrors() as $error) {
                $this->result->addError(
                    $error->getMessage(),
                    $error->getParameters(),
                    $error->getValuePath(),
                );
            }
        }

        throw new NotResolvedException();
    }
}
