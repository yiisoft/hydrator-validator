# Yii Validating Hydrator Change Log

## 2.0.2 December 16, 2025

- Chg #29, #33: Change PHP constraint in `composer.json` to `8.0 - 8.5` (@vjik)
- Bug #31: Clear the `result` property of the `ValidateResolver` object after hydration (@olegbaturin)

## 2.0.1 August 06, 2024

- Enh #24: Add `yiisoft/validator` of version `^2.0` support (@vjik)

## 2.0.0 March 06, 2024

- Chg #17: Throws `LogicException` on call `ValidatedInputInterface::getValidatedInput()` method when object is not
  validated (@vjik)

## 1.0.0 February 02, 2024

- Initial release.
