<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\Validator\Tests\Support\Object\NonValidatedInput;
use Yiisoft\Hydrator\Validator\Tests\Support\Object\SimpleInput;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;

final class ValidatingHydratorTest extends TestCase
{
    public function testSimpleHydrate(): void
    {
        $object = new SimpleInput();

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
            ->create(SimpleInput::class, ['firstName' => 'Bo'])
            ->getValidationResult();

        $this->assertFalse($result->isValid());
        $this->assertSame(
            [
                'firstName' => ['This value must contain at least 3 characters.'],
            ],
            $result->getErrorMessagesIndexedByPath(),
        );
    }

    public function testHydrateNonValidatedInput(): void
    {
        $object = new NonValidatedInput();

        TestHelper::createValidatingHydrator()->hydrate($object, ['a' => 7]);

        $this->assertInstanceOf(NonValidatedInput::class, $object);
        $this->assertSame(7, $object->a);
    }

    public function testCreateNonValidatedInput(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(NonValidatedInput::class, ['a' => 7]);

        $this->assertInstanceOf(NonValidatedInput::class, $object);
        $this->assertSame(7, $object->a);
    }
}
