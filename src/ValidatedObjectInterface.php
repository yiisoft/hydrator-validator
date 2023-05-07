<?php

declare(strict_types=1);

namespace Yiisoft\Input\Validation;

use Yiisoft\Validator\PostValidationHookInterface;
use Yiisoft\Validator\Result;

interface ValidatedObjectInterface extends PostValidationHookInterface
{
    public function getValidationResult(): ?Result;
}
