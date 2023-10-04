<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\AttributeHandling\Exception\UnexpectedAttributeException;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\Tests\Support\Attribute\IncorrectValidateResolver;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;

final class ValidateResolverTest extends TestCase
{
    public function testInvalidAttribute(): void
    {
        $object = new class () {
            #[IncorrectValidateResolver]
            public int $a;
        };

        $hydrator = TestHelper::createValidatingHydrator();

        $this->expectException(UnexpectedAttributeException::class);
        $this->expectExceptionMessage('Expected "' . Validate::class . '", but');
        $hydrator->hydrate($object, ['a' => 7]);
    }
}
