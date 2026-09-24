<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support;

use RuntimeException;
use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Hydrator\Validator\ValidatedInputInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;

#[Validate(new Callback(callback: [ThrowingValidatedInput::class, 'alwaysInvalid']))]
final class ThrowingValidatedInput implements ValidatedInputInterface
{
    private ?Result $result = null;

    public function __construct(public string $value = '') {}

    public static function alwaysInvalid(mixed $value, mixed $rule, mixed $context): Result
    {
        return (new Result())->addError('Validation failed.');
    }

    public function processValidationResult(Result $result): void
    {
        $this->result = $result;
        throw new RuntimeException('Processing failed.');
    }

    public function getValidationResult(): Result
    {
        return $this->result ?? new Result();
    }
}
