---
trigger: model_decision
description: PHPUnit test guidelines
---

Always use Models / Eloquent / Model relationships when possible.
When writing tests, always use model factories instead of mocking models or using hardcoded IDs.
Don't mock eloquent queries. Create the actually necessary models and query them normally as you would do in the application. That should also eliminate the need to do any $modelInstance->shouldReceive calls.
For running tests use: `docker compose exec backend sh -c "SKIP_REFRESH_DB_STATE=1;APP_ENV=testing php artisan tests/Unit/myTest.php"`
Whenever you are done making changes to a phpunit test, run the test again to make sure it passes.
When you haven't figured out why a test fails after 5 or so tries, ask questions that will help you fix it.
When asked to refactor / check a file, always read at least lines 0-500 first.
In factories, when setting up relationships in configure(), always use factories to create the relationships instead of creating them directly.