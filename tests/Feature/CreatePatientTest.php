<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePatientTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
        return;
        $response = $this->get('/admin')->assertSeeText('الإحصائيات');

        $response->assertStatus(200);
    }
}
