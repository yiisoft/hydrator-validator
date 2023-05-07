<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\Support\Object;

use Yiisoft\Input\Validation\ValidatedObjectInterface;
use Yiisoft\Input\Validation\ValidatedObjectTrait;
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
