<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\SensitiveOperationsLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SensitiveOperationsLoggerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function it_logs_successful_operations()
    {
        // Mock the Log facade
        Log::shouldReceive('channel')
            ->once()
            ->with('sensitive_operations')
            ->andReturnSelf();
        
        Log::shouldReceive('info')
            ->once()
            ->with('Sensitive operation performed', \Mockery::on(function ($data) {
                return $data['operation'] === 'test_operation' &&
                       $data['entity'] === 'test_entity' &&
                       $data['entity_id'] === 123 &&
                       $data['status'] === 'success';
            }));
        
        // Act as the user
        Auth::shouldReceive('user')->andReturn($this->user);
        
        // Call the logger
        SensitiveOperationsLogger::success('test_operation', 'test_entity', 123, ['test' => 'data']);
    }
    
    /** @test */
    public function it_logs_failed_operations()
    {
        // Mock the Log facade
        Log::shouldReceive('channel')
            ->once()
            ->with('sensitive_operations')
            ->andReturnSelf();
        
        Log::shouldReceive('info')
            ->once()
            ->with('Sensitive operation performed', \Mockery::on(function ($data) {
                return $data['operation'] === 'test_operation' &&
                       $data['entity'] === 'test_entity' &&
                       $data['entity_id'] === 456 &&
                       $data['status'] === 'failure';
            }));
        
        // Act as the user
        Auth::shouldReceive('user')->andReturn($this->user);
        
        // Call the logger
        SensitiveOperationsLogger::failure('test_operation', 'test_entity', 456, ['error' => 'test error']);
    }
    
    /** @test */
    public function it_logs_attempted_operations()
    {
        // Mock the Log facade
        Log::shouldReceive('channel')
            ->once()
            ->with('sensitive_operations')
            ->andReturnSelf();
        
        Log::shouldReceive('info')
            ->once()
            ->with('Sensitive operation performed', \Mockery::on(function ($data) {
                return $data['operation'] === 'test_operation' &&
                       $data['entity'] === 'test_entity' &&
                       $data['entity_id'] === 789 &&
                       $data['status'] === 'attempt';
            }));
        
        // Act as the user
        Auth::shouldReceive('user')->andReturn($this->user);
        
        // Call the logger
        SensitiveOperationsLogger::attempt('test_operation', 'test_entity', 789, ['attempt' => 'data']);
    }
    
    /** @test */
    public function it_handles_no_authenticated_user()
    {
        // Mock the Log facade
        Log::shouldReceive('channel')
            ->once()
            ->with('sensitive_operations')
            ->andReturnSelf();
        
        Log::shouldReceive('info')
            ->once()
            ->with('Sensitive operation performed', \Mockery::on(function ($data) {
                return $data['operation'] === 'test_operation' &&
                       $data['entity'] === 'test_entity' &&
                       $data['user_id'] === null &&
                       $data['username'] === 'system';
            }));
        
        // No authenticated user
        Auth::shouldReceive('user')->andReturn(null);
        
        // Call the logger
        SensitiveOperationsLogger::log('test_operation', 'test_entity', 999, [], 'success');
    }
}
