<?php

declare(strict_types=1);

namespace Yiisoft\Hydrator\Validator\Tests\TestEnvironments\Php81\Support;

use Yiisoft\Hydrator\Validator\Attribute\Validate;
use Yiisoft\Validator\Rule\Required;

/**
 * Input that doesn't implement `ValidatedInputInterface`, but has a property with `Validate` attribute.
 */
final class NonValidatedInputWithValidate
{
    public function __construct(
        #[Validate(new Required())]
        public string $a = '.',
        public string $b = '.',
    ) {}
}
