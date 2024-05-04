# Yii Validating Hydrator Upgrading Instructions

## Upgrade from 1.x to 2.x

If you use `ValidatedInputInterface::getValidatedInput()` on non-validated inputs or form models, wrap it with
`try ... catch`:

```php
$result = $input->getValidatedInput();
// ↓
try {
    $result = $input->getValidatedInput();
} catch (LogicException) {
    $result = null;
}
```
