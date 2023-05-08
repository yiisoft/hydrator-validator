<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Support;

use Yiisoft\Hydrator\Hydrator;
use Yiisoft\Hydrator\Validator\Attribute\EarlyValidationResolver;
use Yiisoft\Hydrator\Validator\ValidatingHydrator;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\Validator;

final class TestHelper
{
    public static function createValidatingHydrator(): ValidatingHydrator
    {
        $validator = new Validator();
        $earlyValidationResolver = new EarlyValidationResolver($validator);
        $container = new SimpleContainer(
            [
                EarlyValidationResolver::class => $earlyValidationResolver,
            ],
            static fn(string $class) => new $class(),
        );

        return new ValidatingHydrator(
            new Hydrator($container),
            $validator,
            $earlyValidationResolver,
        );
    }
}
