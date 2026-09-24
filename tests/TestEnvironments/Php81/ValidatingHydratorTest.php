<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\AttributeHandling\ResolverFactory\ContainerAttributeResolverFactory;
use Yiisoft\Hydrator\DataInterface;
use Yiisoft\Hydrator\Hydrator;
use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Hydrator\Validator\Attribute\ValidateResolver;
use Yiisoft\Hydrator\Validator\Tests\Support\TestHelper;
use Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support\ThrowingValidatedInput;
use Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support\ValidateInput;
use Yiisoft\Hydrator\Validator\ValidatingHydrator;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\Validator;
use RuntimeException;

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

    public function testResolverCleanupAfterHydrateThrows(): void
    {
        [$hydrator, $validatingHydrator] = $this->createHydrators();
        $object = new ThrowingValidatedInput();

        try {
            $validatingHydrator->hydrate($object);
            $this->fail('Expected validation processing to throw.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Processing failed.', $exception->getMessage());
        }

        $result = $object->getValidationResult();
        $this->assertCount(1, $result->getErrors());
        $hydrator->hydrate(new ValidateInput(), []);
        $this->assertCount(1, $result->getErrors());
    }

    public function testResolverCleanupAfterCreateThrows(): void
    {
        [$hydrator, $validatingHydrator, $validator, $resolver] = $this->createHydrators();
        $object = new ThrowingValidatedInput();
        $returningHydrator = new class ($object) implements HydratorInterface {
            public function __construct(private object $object) {}

            public function hydrate(object $object, array|DataInterface $data = []): void {}

            public function create(string $class, array|DataInterface $data = []): object
            {
                return $this->object;
            }
        };
        $validatingHydrator = new ValidatingHydrator($returningHydrator, $validator, $resolver);

        try {
            $validatingHydrator->create(ThrowingValidatedInput::class);
            $this->fail('Expected validation processing to throw.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Processing failed.', $exception->getMessage());
        }

        $result = $object->getValidationResult();
        $this->assertCount(1, $result->getErrors());
        $hydrator->hydrate(new ValidateInput(), []);
        $this->assertCount(1, $result->getErrors());
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

    private function createHydrators(): array
    {
        $validator = new Validator();
        $validateResolver = new ValidateResolver($validator);
        $container = new SimpleContainer([ValidateResolver::class => $validateResolver]);
        $hydrator = new Hydrator(attributeResolverFactory: new ContainerAttributeResolverFactory($container));

        return [$hydrator, new ValidatingHydrator($hydrator, $validator, $validateResolver), $validator, $validateResolver];
    }
}
