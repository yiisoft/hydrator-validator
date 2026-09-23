# Project Memory

## Raw-input validation lifecycle

- **Current rule:** `ValidatingHydrator` runs repeatable class-level `Validate` rules on the original input before hydration; arrays remain arrays and `DataInterface` objects remain unchanged. Class, property, and parameter raw errors are merged. Raw errors do not stop hydration, but they suppress post-hydration validation.
- **Why:** Request DTOs can enforce payload-wide requirements, unknown-key policies, and cross-field constraints before values are cast, without transport-specific DTO fields.
- **Authority:** [README](../README.md), [ValidatingHydrator](../src/ValidatingHydrator.php).
- **History:** [Class-level raw-input validation](2026-09-23-class-level-validate.md).
- **Revisit:** If `DataInterface` gains a public enumeration API, reconsider whether generic full-payload checks can be supported for it.
