<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation\Attribute;

use Attribute;
use Yiisoft\Hydrator\ParameterAttributeInterface;
use Yiisoft\Validator\RuleInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class EarlyValidation implements ParameterAttributeInterface
{
    /**
     * @var RuleInterface[] $rules
     * @psalm-var list<RuleInterface> $rules
     */
    private array $rules;

    public function __construct(RuleInterface ...$rules)
    {
        $this->rules = array_values($rules);
    }

    /**
     * @return RuleInterface[]
     * @psalm-return list<RuleInterface>
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function getResolver(): string
    {
        return EarlyValidationResolver::class;
    }
}
