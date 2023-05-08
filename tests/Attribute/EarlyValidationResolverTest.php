<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\UnexpectedAttributeException;
use Yiisoft\Hydrator\Validator\Attribute\EarlyValidation;
use Yiisoft\Hydrator\Validator\Tests\Support\Attribute\IncorrectEarlyValidationResolver;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;

final class EarlyValidationResolverTest extends TestCase
{
    public function testInvalidAttribute(): void
    {
        $object = new class () {
            #[IncorrectEarlyValidationResolver]
            public int $a;
        };

        $hydrator = TestHelper::createValidatingHydrator();

        $this->expectException(UnexpectedAttributeException::class);
        $this->expectExceptionMessage('Expected "' . EarlyValidation::class . '", but');
        $hydrator->hydrate($object, ['a' => 7]);
    }
}
