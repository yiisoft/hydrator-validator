<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator;

use Yiisoft\Validator\PostValidationHookInterface;
use Yiisoft\Validator\Result;

/**
 * `ValidatedInputInterface` is an interface for objects that can be validated.
 * It provides a method to get validation result.
 *
 * You can use {@see ValidatedInputTrait} to implement this interface.
 */
interface ValidatedInputInterface extends PostValidationHookInterface
{
    /**
     * Returns validation result.
     *
     * @return Result|null Validation result.
     */
    public function getValidationResult(): ?Result;
}
