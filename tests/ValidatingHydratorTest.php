<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Input\Validation\Tests\Support\Object\NonValidatedObject;
use Yiisoft\Input\Validation\Tests\Support\Object\SimpleObject;
use Yiisoft\Input\Validation\Tests\Support\TestHelper;

final class ValidatingHydratorTest extends TestCase
{
    public function testSimpleHydrate(): void
    {
        $object = new SimpleObject();

        TestHelper::createValidatingHydrator()->hydrate($object, ['firstName' => 'Bo']);
        $result = $object->getValidationResult();

        $this->assertFalse($result->isValid());
        $this->assertSame(
            [
                'firstName' => ['This value must contain at least 3 characters.'],
            ],
            $result->getErrorMessagesIndexedByPath(),
        );
    }

    public function testSimpleCreate(): void
    {
        $result = TestHelper::createValidatingHydrator()
            ->create(SimpleObject::class, ['firstName' => 'Bo'])
            ->getValidationResult();

        $this->assertFalse($result->isValid());
        $this->assertSame(
            [
                'firstName' => ['This value must contain at least 3 characters.'],
            ],
            $result->getErrorMessagesIndexedByPath(),
        );
    }

    public function testHydrateNonValidatedObject(): void
    {
        $object = new NonValidatedObject();

        TestHelper::createValidatingHydrator()->hydrate($object, ['a' => 7]);

        $this->assertInstanceOf(NonValidatedObject::class, $object);
        $this->assertSame(7, $object->a);
    }

    public function testCreateNonValidatedObject(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(NonValidatedObject::class, ['a' => 7]);

        $this->assertInstanceOf(NonValidatedObject::class, $object);
        $this->assertSame(7, $object->a);
    }
}
