<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii Validating Hydrator</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/hydrator-validator/v/stable.png)](https://packagist.org/packages/yiisoft/hydrator-validator)
[![Total Downloads](https://poser.pugx.org/yiisoft/hydrator-validator/downloads.png)](https://packagist.org/packages/yiisoft/hydrator-validator)
[![Build status](https://github.com/yiisoft/hydrator-validator/workflows/build/badge.svg)](https://github.com/yiisoft/hydrator-validator/actions?query=workflow%3Abuild)
[![Code Coverage](https://codecov.io/gh/yiisoft/hydrator-validator/branch/master/graph/badge.svg)](https://codecov.io/gh/yiisoft/hydrator-validator)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fhydrator-validator%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/hydrator-validator/master)
[![static analysis](https://github.com/yiisoft/hydrator-validator/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/hydrator-validator/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/hydrator-validator/coverage.svg)](https://shepherd.dev/github/yiisoft/hydrator-validator)
[![psalm-level](https://shepherd.dev/github/yiisoft/hydrator-validator/level.svg)](https://shepherd.dev/github/yiisoft/hydrator-validator)

The package provides [a hydrator](https://github.com/yiisoft/hydrator)
that also does [validation](https://github.com/yiisoft/validator), including raw data.
It's useful when input data comes from a user, and you need to validate it and then put it into DTOs.

## Requirements

- PHP 8.0 or higher.

## Installation

The package could be installed with composer:

```shell
composer require yiisoft/hydrator-validator
```

## General usage

Validating hydrator is a decorator for [another hydrator](https://github.com/yiisoft/hydrator).
It validates data before passing it to the decorated hydrator.

To use it, the object being validated must implement `ValidatedInputInterface`.
Validation result could be obtained via its `getValidationResult()` method.

```php
use \Yiisoft\Hydrator\HydratorInterface;

public function actionEdit(RequestInterface $request, HydratorInterface $hydrator): ResponseInterface
{
    $data = $request->getParsedBody();    
    $inputDto = $hydrator->create(EditActionInput $input, $data);
    
    if (!$inputDto->getValidationResult()->isValid()) {
        // Validation didn't pass :(
    }
    
    // Everything is fine. You can use $inputDto now.    
}
```

The validation rules for the DTO are defined with `Validate` PHP attributes:

```php
final class EditActionInput
{
    #[Validate(Email::rule())]
    private string $email;
    
    #[Validate(Required::rule())]
    private string $name;
    
    // ...
}
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework with
[Infection Static Analysis Plugin](https://github.com/Roave/infection-static-analysis-plugin). To run it:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Code style

Use [Rector](https://github.com/rectorphp/rector) to make codebase follow some specific rules or 
use either newest or any specific version of PHP: 

```shell
./vendor/bin/rector
```

### Dependencies

Use [ComposerRequireChecker](https://github.com/maglnet/ComposerRequireChecker) to detect transitive 
[Composer](https://getcomposer.org/) dependencies.

## License

The Yii Validating Hydrator is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
