<?php
declare(strict_types=1);

namespace Yiisoft\Input\Validation;

use Yiisoft\Validator\Result;

trait ValidatedInputTrait
{
    #[NoPopulate]
    private ?Result $validationResult = null;

    public function processValidationResult(Result $result): void
    {
        $this->validationResult = $result;
    }

    public function getValidationResult(): ?Result
    {
        return $this->validationResult;
    }
}
