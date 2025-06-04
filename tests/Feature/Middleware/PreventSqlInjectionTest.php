<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\PreventSqlInjection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;

class PreventSqlInjectionTest extends TestCase
{
    protected function createTestMiddleware(): PreventSqlInjection
    {
        return new PreventSqlInjection();
    }

    protected function createRequestForSqlTest(array $data = [], string $method = 'GET'): Request
    {
        // The $parameters argument for Request::create serves as query for GET
        // and request body for POST/PUT etc.
        // The first argument is the URI, can be simple like '/' for testing middleware.
        $request = Request::create('/', $method, $data);
        return $request;
    }

    /** @test */
    public function it_allows_clean_requests()
    {
        $middleware = $this->createTestMiddleware();
        
        // Test GET request
        $request = $this->createRequestForSqlTest([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'search' => 'dental checkup',
        ]);

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
        
        // Test POST request
        $request = $this->createRequestForSqlTest([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'I would like to schedule an appointment',
        ], 'POST');

        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_sql_commands_in_query_parameters()
    {
        $middleware = $this->createTestMiddleware();
        
        $sqlCommands = [
            "SELECT * FROM users",
            "DROP TABLE users",
            "1' OR '1'='1",
            "admin'--",
            "1' UNION SELECT * FROM users--",
            "1; DROP TABLE users--",
            "1' OR 1=1--",
            "1' OR '1'='1' /*",
            "1' OR '1'='1' #",
        ];

        foreach ($sqlCommands as $sql) {
            $request = $this->createRequestForSqlTest(['search' => $sql]);
            
            $response = $middleware->handle($request, function ($req) {
                return new Response('Should not reach here', 200);
            });

            $this->assertEquals(422, $response->getStatusCode(), "Failed to block SQL: {$sql}");
            $responseData = json_decode($response->getContent(), true);
            $this->assertArrayHasKey('errors', $responseData);
            $this->assertArrayHasKey('input', $responseData['errors']);
        }
    }

    /** @test */
    public function it_blocks_sql_commands_in_nested_arrays()
    {
        $middleware = $this->createTestMiddleware();
        
        $request = $this->createRequestForSqlTest([
            'filters' => [
                'search' => "1' OR '1'='1",
                'sort' => [
                    'field' => 'name',
                    'order' => "ASC; DROP TABLE users--"
                ]
            ]
        ]);

        $response = $middleware->handle($request, function ($req) {
            return new Response('Should not reach here', 200);
        });

        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_allows_safe_special_characters()
    {
        $middleware = $this->createTestMiddleware();
        
        $safeInputs = [
            'email' => 'user.name+tag@example.com',
            'search' => 'dental & cleaning',
            'comment' => 'This is a test with special chars: !@#$%^&*()_+{}[]|\\:;\'\",.<>/?`~',
        ];

        $request = $this->createRequestForSqlTest($safeInputs);
        
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_handles_empty_requests() 
    {
        $middleware = $this->createTestMiddleware();
        
        // Test empty GET request
        $request = $this->createRequestForSqlTest();
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });
        $this->assertEquals(200, $response->getStatusCode());
        
        // Test empty POST request
        $request = $this->createRequestForSqlTest([], 'POST');
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_handles_non_string_values()
    {
        $middleware = $this->createTestMiddleware();
        
        $request = $this->createRequestForSqlTest([
            'number' => 123,
            'array' => ['a', 'b', 'c'],
            'null' => null,
            'boolean' => true,
            'nested' => [
                'number' => 456,
                'text' => 'normal text',
                'empty' => ''
            ]
        ]);
        
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /** @test */
    public function it_logs_suspicious_activity()
    {
        Log::shouldReceive('channel->warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Potential SQL injection attempt detected' &&
                       isset($context['suspicious_input']);
            });
        
        $middleware = $this->createTestMiddleware();
        $request = $this->createRequestForSqlTest([
            'search' => "1' OR '1'='1"
        ]);
        
        $response = $middleware->handle($request, function ($req) {
            return new Response('Should not reach here', 200);
        });
        
        $this->assertEquals(422, $response->getStatusCode());
    }
}
