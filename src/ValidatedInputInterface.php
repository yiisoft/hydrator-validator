<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator;

use Yiisoft\Validator\PostValidationHookInterface;
use Yiisoft\Validator\Result;

interface ValidatedInputInterface extends PostValidationHookInterface
{
    public function getValidationResult(): ?Result;
}
