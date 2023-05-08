<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Support\Attribute;

use Attribute;
use Yiisoft\Hydrator\ParameterAttributeInterface;
use Yiisoft\Hydrator\Validator\Attribute\EarlyValidationResolver;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class IncorrectEarlyValidationResolver implements ParameterAttributeInterface
{
    public function getResolver(): string
    {
        return EarlyValidationResolver::class;
    }
}
