<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support;

use Yiisoft\Hydrator\DataInterface;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\ValidatedInputInterface;
use Yiisoft\Hydrator\Validator\ValidatedInputTrait;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

use function array_key_exists;
use function is_array;
use function is_int;

#[Validate(new Callback(callback: [ClassLevelValidateInput::class, 'hasName']))]
#[Validate(new Callback(callback: [ClassLevelValidateInput::class, 'hasRawIntegerAge']))]
#[Validate(new Callback(callback: [ClassLevelValidateInput::class, 'hasOnlyKnownKeys']))]
final class ClassLevelValidateInput implements ValidatedInputInterface
{
    use ValidatedInputTrait;

    public function __construct(
        #[Validate(new Required(notPassedMessage: 'The name is required in raw input.'))]
        #[Length(min: 3, lessThanMinMessage: 'The hydrated name is too short.')]
        public string $name = '',
        public int $age = 0,
    ) {}

    public static function hasName(mixed $value, mixed $rule, mixed $context): Result
    {
        $valid = $value instanceof DataInterface
            ? $value->getValue('name')->isResolved()
            : is_array($value) && array_key_exists('name', $value);

        return $valid ? new Result() : (new Result())->addError('The payload must contain a name.');
    }

    public static function hasRawIntegerAge(mixed $value, mixed $rule, mixed $context): Result
    {
        if ($value instanceof DataInterface) {
            $age = $value->getValue('age');
            $valid = $age->isResolved() && is_int($age->getValue());
        } else {
            $valid = is_array($value) && array_key_exists('age', $value) && is_int($value['age']);
        }

        return $valid
            ? new Result()
            : (new Result())->addError('The payload age must be an integer in the raw input.');
    }

    public static function hasOnlyKnownKeys(mixed $value, mixed $rule, mixed $context): Result
    {
        $valid = $value instanceof DataInterface
            || is_array($value) && !array_diff(array_keys($value), ['name', 'age']);

        return $valid ? new Result() : (new Result())->addError('The payload contains an unknown key.');
    }
}
