<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Support;

use Yiisoft\Hydrator\AttributeHandling\ResolverFactory\ContainerAttributeResolverFactory;
use Yiisoft\Hydrator\Hydrator;
use Yiisoft\Hydrator\ObjectFactory\ContainerObjectFactory;
use Yiisoft\Hydrator\Validator\Attribute\ValidateResolver;
use Yiisoft\Hydrator\Validator\ValidatingHydrator;
use Yiisoft\Injector\Injector;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\Validator;

final class TestHelper
{
    public static function createValidatingHydrator(): ValidatingHydrator
    {
        $validator = new Validator();
        $validateResolver = new ValidateResolver($validator);
        $container = new SimpleContainer(
            [
                ValidateResolver::class => $validateResolver,
            ],
            static fn(string $class) => new $class(),
        );

        return new ValidatingHydrator(
            new Hydrator(
                attributeResolverFactory: new ContainerAttributeResolverFactory($container),
                objectFactory: new ContainerObjectFactory(
                    new Injector($container)
                ),
            ),
            $validator,
            $validateResolver,
        );
    }
}
