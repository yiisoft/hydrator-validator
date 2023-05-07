<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\Support\Object;

final class NonValidatedObject
{
    public function __construct(
        public int $a = 0,
    ) {
    }
}
