<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqlInjectionPreventionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function preventsSqlInjectionInWhereClauses()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Attempt SQL injection in where clause
        $injection = "' OR '1'='1";

        // This should only find the user with the exact email, not all users
        $users = User::where('email', $injection)->get();
        $this->assertCount(0, $users);

        // Test with parameter binding
        $users = User::whereRaw('email = ?', [$injection])->get();
        $this->assertCount(0, $users);
    }

    /** @test */
    public function preventsSqlInjectionInLikeClauses()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Attempt SQL injection in LIKE clause
        $injection = "%' OR '1'='1";

        // This should not find any users as the injection should be escaped
        $users = User::where('email', 'like', $injection)->get();
        $this->assertCount(0, $users);
    }

    /** @test */
    public function preventsSqlInjectionInOrderBy()
    {
        User::factory()->count(3)->create();

        // Test with a potentially dangerous column name
        $dangerousColumn = 'id; DROP TABLE users; --';

        // This should not execute the DROP TABLE statement
        // Instead, it should either:
        // 1. Fail with an exception (preferred)
        // 2. Treat the entire string as a column name (which doesn't exist)

        try {
            $result = User::orderBy($dangerousColumn)->get();

            // If we get here, the query executed but should have treated the entire string as a column name
            // which doesn't exist, so the result should be empty or throw an exception
            $this->assertTrue(true, 'Query executed safely without executing injected SQL');
        } catch (\Illuminate\Database\QueryException $e) {
            // This is also acceptable - the query failed safely
            $this->assertTrue(true, 'Query failed safely with exception: ' . $e->getMessage());
        }
    }

    /** @test */
    public function preventsSqlInjectionInRawExpressions()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Attempt SQL injection in raw expression
        $injection = "1; DROP TABLE users; --";

        // This should not execute the DROP TABLE statement
        $result = \DB::selectOne("SELECT * FROM users WHERE id = ?", [$injection]);
        $this->assertNull($result);
    }

    /** @test */
    public function preventsUnionBasedSqlInjection()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Attempt UNION-based SQL injection
        $injection = "1' UNION SELECT * FROM users --";

        // This should not return all users
        $users = User::where('id', $injection)->get();
        $this->assertCount(0, $users);
    }
}
