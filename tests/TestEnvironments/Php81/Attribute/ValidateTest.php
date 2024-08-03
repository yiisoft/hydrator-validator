<?php

declare(strict_types=1);

namespace Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;
use Yiisoft\Hydrator\Validator\ValidatedInputInterface;
use Yiisoft\Hydrator\Validator\ValidatedInputTrait;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Number;

final class ValidateTest extends TestCase
{
    public function testResolvedValue(): void
    {
        $hydrator = TestHelper::createValidatingHydrator();

        $object = new class () implements ValidatedInputInterface {
            use ValidatedInputTrait;

            #[Validate(new Number(min: 7))]
            public int $value = 0;
        };

        $hydrator->hydrate($object, ['value' => 6]);
        $result = $object->getValidationResult();

        $this->assertInstanceOf(Result::class, $result);
        $this->assertSame(
            ['value' => ['Value must be no less than 7.']],
            $result->getErrorMessagesIndexedByPath(),
        );
    }
}
