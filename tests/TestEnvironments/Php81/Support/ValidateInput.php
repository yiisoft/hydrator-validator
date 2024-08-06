<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support;

use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\ValidatedInputInterface;
use Yiisoft\Hydrator\Validator\ValidatedInputTrait;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class ValidateInput implements ValidatedInputInterface
{
    use ValidatedInputTrait;

    public function __construct(
        #[Validate(new Required(notPassedMessage: 'Value not passed.'))]
        #[Length(min: 3)]
        public string $a = '.',
        public string $b = '.',
        public string $c = '.',
    ) {
    }
}
