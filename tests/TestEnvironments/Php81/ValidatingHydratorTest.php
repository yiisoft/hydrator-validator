<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;
use Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support\ValidateObject;

final class ValidatingHydratorTest extends TestCase
{
    public function testCreateWithValidateAttribute(): void
    {
        $result = TestHelper::createValidatingHydrator()
            ->create(ValidateObject::class)
            ->getValidationResult();

        $this->assertFalse($result->isValid());
        $this->assertSame(
            [
                'a' => ['Value not passed.'],
            ],
            $result->getErrorMessagesIndexedByPath(),
        );
    }

    public function testHydrateWithValidateAttribute(): void
    {
        $object = new ValidateObject();

        TestHelper::createValidatingHydrator()->hydrate($object, ['b' => 'y', 'c' => 'z']);

        $result = $object->getValidationResult();

        $this->assertFalse($result->isValid());
        $this->assertSame(
            [
                'a' => ['Value not passed.'],
            ],
            $result->getErrorMessagesIndexedByPath(),
        );
        $this->assertSame('y', $object->b);
        $this->assertSame('z', $object->c);
    }
}
