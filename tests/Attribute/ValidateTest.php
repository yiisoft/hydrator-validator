<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\Attribute\ValidateResolver;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class ValidateTest extends TestCase
{
    public function testBase(): void
    {
        $sourceRules = [new Required(), new Length(min: 3)];

        $attribute = new Validate(...$sourceRules);

        $rules = $attribute->getRules();
        $resolver = $attribute->getResolver();

        $this->assertSame($sourceRules, $rules);
        $this->assertSame(ValidateResolver::class, $resolver);
    }

    public function testNamedArguments(): void
    {
        $rule1 = new Required();
        $rule2 = new Length(min: 3);

        $attribute = new Validate(a: $rule1, b: $rule2);

        $this->assertSame([$rule1, $rule2], $attribute->getRules());
    }
}
