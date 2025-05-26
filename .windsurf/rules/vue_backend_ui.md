---
trigger: glob
description: Vue 3 frontend UI standards and CSS naming conventions
globs: **/*.{vue,ts}
---

When referencing constants, use getConstants() or $constants (in vue templates). All constants are defined in constants.yaml.
We are using vue 3. Always use <script setup lang="ts">
For css class names, they always follow the convention of .s-<name>__<childName> and .-<modifierName> for example: s-courseCard__header and -large for a large card header.
All user-visible text must use our translation system. We use vue-i18n, e.g. $t("moduleName.translationKey") to output translated values. Our translations are stored in: resources/js/lang/en.json Always check them first if a matching translation exists already. If not, add translations for de, en and ar.

I use tailwindcss for styling, when i add a new componenet in resources/js/components folder i'm adding it also to clinicComponent.ts and to components.d.ts and the name in clinicComponent should be prefixed by `C` but the name of the component file shouldn't have this `C` char.

Don't use axios, we have api class to send out apis.