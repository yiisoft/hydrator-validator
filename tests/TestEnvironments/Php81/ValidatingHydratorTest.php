<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\TestEnvironments\Php81;

use PHPUnit\Framework\TestCase;
use Yiisoft\Input\Validation\Tests\Support\TestHelper;
use Yiisoft\Input\Validation\Tests\TestEnvironments\Php81\Support\EarlyValidationObject;

final class ValidatingHydratorTest extends TestCase
{
    public function testCreateWithEarlyValidation(): void
    {
        $result = TestHelper::createValidatingHydrator()
            ->create(EarlyValidationObject::class)
            ->getValidationResult();

        $this->assertFalse($result->isValid());
        $this->assertSame(
            [
                'a' => ['Value not passed.'],
            ],
            $result->getErrorMessagesIndexedByPath(),
        );
    }

    public function testHydrateWithEarlyValidation(): void
    {
        $object = new EarlyValidationObject();

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
