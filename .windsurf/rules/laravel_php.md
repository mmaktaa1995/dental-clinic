---
trigger: glob
description: Laravel/PHP code style and usage guidelines
globs: **/*.php
---

We are using Laravel 10.
Use Laravel functionality as much as possible.
When needing information about the db schema, search for `IdeHelperModelNameHere {}` in \_ide_helper_models.php.
If you modify or create a model's properties, suggest running `php artisan ide-helper:models -M`.
When running `php artisan ide-helper:models` always run it with the -M flag.
Don't create custom helpers for repsonse just use the provided from laravel.
Do the code to be compatible with php standards.
Don't use repository pattern i'm using Services and implement only the needed methods in these service depnding on what we have in our controllers.
Don't change in exisitng routes structure.

use app_configs for general variables to be used across the app.
