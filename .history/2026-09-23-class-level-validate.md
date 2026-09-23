# Support class-level raw-input validation

## Problem

Input DTOs need to validate the complete uncast payload before hydration (for example, sparse-update requirements, unknown fields, and cross-field constraints) without adding transport-specific state to the DTO.

## Decision and approach

Extend the existing repeatable `Validate` attribute to classes and run those validators against the original payload before delegating hydration. Merge their errors with property/parameter raw-validation errors. Preserve the current lifecycle: raw-validation failures do not stop hydration, and any raw-validation failure suppresses post-hydration validation while remaining available through `ValidatedInputInterface`.

## Implemented

`Validate` is now allowed and repeatable on classes. `ValidatingHydrator` validates the complete original input before `create()` / `hydrate()`, merges class-level errors with property/parameter errors, and preserves the existing lifecycle: raw errors do not stop hydration but suppress post-hydration validation. Array input and `DataInterface` objects are passed to class rules unchanged. README documents the contract and limitation that `DataInterface` cannot be generically enumerated.

## Validation

- Before implementation, focused regression tests failed for the expected unsupported class-level behavior (7 tests, 14 assertions, 4 failures).
- After implementation, full PHPUnit passed (20 tests, 52 assertions) in Docker PHP 8.5.10.
- Psalm passed with no errors and 100% type inference.
- PHP-CS-Fixer dry-run and `git diff --check` passed.

## Follow-up

Run the package dependency matrix on CI, especially the supported Validator 1.x and PHP 8.0 combinations; local verification used Validator 2.6.1 and PHP 8.5.10.
