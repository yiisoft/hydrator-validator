<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support;

use Yiisoft\Hydrator\Validator\Attribute\EarlyValidation;
use Yiisoft\Hydrator\Validator\ValidatedObjectInterface;
use Yiisoft\Hydrator\Validator\ValidatedObjectTrait;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class EarlyValidationObject implements ValidatedObjectInterface
{
    use ValidatedObjectTrait;

    public function __construct(
        #[EarlyValidation(new Required())]
        #[Length(min: 3)]
        public string $a = '.',
        public string $b = '.',
        public string $c = '.',
    ) {
    }
}
