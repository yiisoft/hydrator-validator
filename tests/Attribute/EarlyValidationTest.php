<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\Input\Validation\Attribute\EarlyValidation;
use Yiisoft\Input\Validation\Attribute\EarlyValidationResolver;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

final class EarlyValidationTest extends TestCase
{
    public function testBase(): void
    {
        $sourceRules = [new Required(), new Length(min: 3)];

        $attribute = new EarlyValidation(...$sourceRules);

        $rules = $attribute->getRules();
        $resolver = $attribute->getResolver();

        $this->assertSame($sourceRules, $rules);
        $this->assertSame(EarlyValidationResolver::class, $resolver);
    }

    public function testNamedArguments(): void
    {
        $rule1 = new Required();
        $rule2 = new Length(min: 3);

        $attribute = new EarlyValidation(a: $rule1, b: $rule2);

        $this->assertSame([$rule1, $rule2], $attribute->getRules());
    }
}
