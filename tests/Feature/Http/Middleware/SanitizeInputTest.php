<?php

namespace Tests\Feature\Http\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SanitizeInputTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sanitizesInputData()
    {
        // Create a test route that returns the request input
        $this->app['router']->post('/test-sanitize', function (\Illuminate\Http\Request $request) {
            return response()->json($request->all());
        })->middleware('web');

        // Test data with potential XSS and other malicious content
        $maliciousInput = [
            'html' => '<script>alert("XSS")</script>Test',
            'onclick' => 'onclick="alert(\'XSS\')"',
            'javascript' => 'javascript:alert(1)',
            'style' => 'style="width: expression(alert(1))"',
            'nested' => [
                'html' => '<b>Bold</b>',
                'script' => '<script>alert(1)</script>',
            ],
        ];

        $response = $this->post('/test-sanitize', $maliciousInput);

        $response->assertStatus(200);

        $data = $response->json();

        // Assert that HTML tags are stripped or escaped
        $this->assertEquals('&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;Test', $data['html']);
        $this->assertEquals('onclick=&quot;alert(&apos;XSS&apos;)&quot;', $data['onclick']);
        $this->assertEquals('alert(1)', $data['javascript']); // javascript: protocol is removed for security
        $this->assertStringStartsWith('style=&quot;', $data['style']);
        $this->assertStringEndsWith('&quot;', $data['style']);
        $this->assertStringContainsString('width:', $data['style']);

        // Test nested data
        $this->assertEquals('&lt;b&gt;Bold&lt;/b&gt;', $data['nested']['html']);
        $this->assertEquals('&lt;script&gt;alert(1)&lt;/script&gt;', $data['nested']['script']);
    }

    /** @test */
    public function allowsSafeHtmlWhenNeeded()
    {
        // Create a test route that returns the request input
        $this->app['router']->post('/test-safe-html', function (\Illuminate\Http\Request $request) {
            return response()->json($request->all());
        })->middleware('web');

        $safeHtml = [
            'bold' => '<b>Bold text</b>',
            'link' => '<a href="https://example.com">Link</a>',
            'paragraph' => '<p>Paragraph</p>',
            'list' => '<ul><li>Item 1</li><li>Item 2</li></ul>',
        ];

        $response = $this->post('/test-safe-html', $safeHtml);

        $response->assertStatus(200);

        $data = $response->json();

        // Assert that HTML is properly escaped for security
        $this->assertEquals('&lt;b&gt;Bold text&lt;/b&gt;', $data['bold']);
        $this->assertEquals('&lt;a href=&quot;https://example.com&quot;&gt;Link&lt;/a&gt;', $data['link']);
        $this->assertEquals('&lt;p&gt;Paragraph&lt;/p&gt;', $data['paragraph']);
        $this->assertEquals(
            '&lt;ul&gt;&lt;li&gt;Item 1&lt;/li&gt;&lt;li&gt;Item 2&lt;/li&gt;&lt;/ul&gt;',
            $data['list']
        );
    }
}
