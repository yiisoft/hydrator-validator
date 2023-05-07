<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\UnexpectedAttributeException;
use Yiisoft\Input\Validation\Attribute\EarlyValidation;
use Yiisoft\Input\Validation\Tests\Support\Attribute\IncorrectEarlyValidationResolver;
use Yiisoft\Input\Validation\Tests\Support\TestHelper;

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
