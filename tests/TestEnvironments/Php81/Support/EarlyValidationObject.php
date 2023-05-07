<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\TestEnvironments\Php81\Support;

use Yiisoft\Input\Validation\Attribute\EarlyValidation;
use Yiisoft\Input\Validation\ValidatedObjectInterface;
use Yiisoft\Input\Validation\ValidatedObjectTrait;
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
