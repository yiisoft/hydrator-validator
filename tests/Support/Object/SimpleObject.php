<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Support\Object;

use Yiisoft\Hydrator\Validator\ValidatedObjectInterface;
use Yiisoft\Hydrator\Validator\ValidatedObjectTrait;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class SimpleObject implements ValidatedObjectInterface
{
    use ValidatedObjectTrait;

    public function __construct(
        #[Required]
        #[Length(min: 3)]
        public string $firstName = '',
        #[Length(min: 3, skipOnEmpty: true)]
        public string $lastName = '',
    ) {
    }
}
