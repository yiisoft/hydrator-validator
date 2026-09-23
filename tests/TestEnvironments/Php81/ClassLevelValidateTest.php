<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\ArrayData;
use Yiisoft\Hydrator\Exception\NonExistClassException;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;
use Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support\ClassLevelValidateInput;

final class ClassLevelValidateTest extends TestCase
{
    public function testCreateMissingClassThrowsNonExistClassException(): void
    {
        $this->expectException(NonExistClassException::class);

        TestHelper::createValidatingHydrator()->create(
            'Yiisoft\\Hydrator\\Validator\\Tests\\TestEnvironments\\Php81\\MissingClass',
        );
    }

    public function testValidPayloadIsAcceptedByCreateAndRawValuesAreAvailableToClassRules(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(
            ClassLevelValidateInput::class,
            ['name' => 'Alice', 'age' => 7],
        );

        $this->assertTrue($object->getValidationResult()->isValid());
        $this->assertSame(7, $object->age);
    }

    public function testValidPayloadIsAcceptedByHydrate(): void
    {
        $object = new ClassLevelValidateInput();

        TestHelper::createValidatingHydrator()->hydrate($object, ['name' => 'Alice', 'age' => 7]);

        $this->assertTrue($object->getValidationResult()->isValid());
        $this->assertSame('Alice', $object->name);
        $this->assertSame(7, $object->age);
    }

    public function testEmptyPayloadReportsClassAndPropertyRawErrorsAndStillHydrates(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(ClassLevelValidateInput::class);
        $messages = $object->getValidationResult()->getErrorMessagesIndexedByPath();

        $this->assertFalse($object->getValidationResult()->isValid());
        $this->assertContains('The payload must contain a name.', $messages[''] ?? []);
        $this->assertContains('The payload age must be an integer in the raw input.', $messages[''] ?? []);
        $this->assertContains('The name is required in raw input.', $messages['name']);
        $this->assertNotContains('The hydrated name is too short.', $messages['name'] ?? []);
        $this->assertSame('', $object->name);
    }

    public function testUnknownKeyIsValidatedAgainstTheCompleteRawPayload(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(
            ClassLevelValidateInput::class,
            ['name' => 'Alice', 'age' => 7, 'unexpected' => 'value'],
        );
        $messages = $object->getValidationResult()->getErrorMessagesIndexedByPath();

        $this->assertFalse($object->getValidationResult()->isValid());
        $this->assertContains('The payload contains an unknown key.', $messages[''] ?? []);
        $this->assertSame('Alice', $object->name);
        $this->assertSame(7, $object->age);
    }

    public function testClassRulesReceiveInvalidRawValuesBeforeHydrationAndSuppressPostValidation(): void
    {
        $object = new ClassLevelValidateInput();

        TestHelper::createValidatingHydrator()->hydrate($object, ['name' => 'x', 'age' => '7']);
        $messages = $object->getValidationResult()->getErrorMessagesIndexedByPath();

        $this->assertFalse($object->getValidationResult()->isValid());
        $this->assertContains('The payload age must be an integer in the raw input.', $messages[''] ?? []);
        $this->assertNotContains('The hydrated name is too short.', $messages['name'] ?? []);
        $this->assertSame('x', $object->name);
        $this->assertSame(7, $object->age);
    }

    public function testDataInterfaceIsPassedUnchangedToClassRulesBeforeHydration(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(
            ClassLevelValidateInput::class,
            new ArrayData(['name' => 'Alice', 'age' => '7']),
        );
        $messages = $object->getValidationResult()->getErrorMessagesIndexedByPath();

        $this->assertFalse($object->getValidationResult()->isValid());
        $this->assertContains('The payload age must be an integer in the raw input.', $messages[''] ?? []);
        $this->assertSame(7, $object->age);
    }

    public function testSuccessfulClassLevelRawValidationStillRunsPostHydrationValidation(): void
    {
        $object = TestHelper::createValidatingHydrator()->create(
            ClassLevelValidateInput::class,
            ['name' => 'x', 'age' => 7],
        );
        $messages = $object->getValidationResult()->getErrorMessagesIndexedByPath();

        $this->assertFalse($object->getValidationResult()->isValid());
        $this->assertSame(['The hydrated name is too short.'], $messages['name']);
        $this->assertSame([], $messages[''] ?? []);
    }
}
