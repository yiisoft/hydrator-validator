<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Support\Object;

final class NonValidatedInput
{
    public function __construct(
        public int $a = 0,
    ) {
    }
}
