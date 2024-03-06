<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests;

use LogicException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\Validator\Tests\Support\Object\SimpleInput;

final class ValidatedInputTraitTest extends TestCase
{
    public function testNotValidate(): void
    {
        $input = new SimpleInput();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Validation result is not set.');
        $input->getValidationResult();
    }
}
