<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\Support;

use Yiisoft\Hydrator\Hydrator;
use Yiisoft\Input\Validation\Attribute\EarlyValidationResolver;
use Yiisoft\Input\Validation\ValidatingHydrator;
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
