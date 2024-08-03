<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Support\Object;

use Yiisoft\Hydrator\Validator\ValidatedInputInterface;
use Yiisoft\Hydrator\Validator\ValidatedInputTrait;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class SimpleInput implements ValidatedInputInterface
{
    use ValidatedInputTrait;

    public function __construct(
        #[Required]
        #[Length(min: 3, lessThanMinMessage: 'This value must contain at least 3 characters.')]
        public string $firstName = '',
        #[Length(min: 3, skipOnEmpty: true)]
        public string $lastName = '',
    ) {
    }
}
