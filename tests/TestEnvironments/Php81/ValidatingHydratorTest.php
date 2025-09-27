<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\AttributeHandling\ResolverFactory\ContainerAttributeResolverFactory;
use Yiisoft\Hydrator\Hydrator;
use Yiisoft\Hydrator\Validator\Attribute\ValidateResolver;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;
use Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support\ValidateInput;
use Yiisoft\Hydrator\Validator\ValidatingHydrator;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\Validator;

final class ValidatingHydratorTest extends TestCase
{
    public function testCreateWithValidateAttribute(): void
    {
        $result = TestHelper::createValidatingHydrator()
            ->create(ValidateInput::class)
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
        $object = new ValidateInput();

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

    public function testValidateResolverCleanupAfterCreate(): void
    {
        $validator = new Validator();
        $validateResolver = new ValidateResolver($validator);
        $container = new SimpleContainer(
            [
                ValidateResolver::class => $validateResolver,
            ],
        );

        $hydrator = new Hydrator(
            attributeResolverFactory: new ContainerAttributeResolverFactory($container),
        );
        $validatingHydrator = new ValidatingHydrator($hydrator, $validator, $validateResolver);

        $object = new ValidateInput();
        $validatingHydrator->hydrate($object, ['b' => 'y', 'c' => 'z']);
        $result = $object->getValidationResult();
        $this->assertCount(1, $result->getErrors());

        $hydrator->hydrate(new ValidateInput(), ['b' => 'y', 'c' => 'z']);
        $this->assertCount(1, $result->getErrors());
    }
}
