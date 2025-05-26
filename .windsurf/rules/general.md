---
trigger: always_on
---

Never rewrite code to use a ternary operator if it didn't use it before.
Prefer using early returns instead of nesting if statements.

User mysql for testing not sqlite