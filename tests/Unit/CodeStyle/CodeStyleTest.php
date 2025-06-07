<?php

namespace Tests\Unit\CodeStyle;

use PHP_CodeSniffer\Runner;
use PHP_CodeSniffer\Config;
use PHPUnit\Framework\TestCase;

class CodeStyleTest extends TestCase
{
    /** @test */
    public function itPassesPhpCsValidation()
    {
        // Get the path to the PHP_CodeSniffer configuration
        $config = new Config([
            '--standard=' . __DIR__ . '/../../../../phpcs.xml.dist',
            '--report=json',
            '--ignore=*/vendor/*,*/storage/*,*/bootstrap/cache/*,*/node_modules/*,*/public/*,*/resources/views/*',
            __DIR__ . '/../../..',
        ]);

        // Set the report width to 200 to prevent line length issues in the output
        $config->reportWidth = 200;
        $config->reports = ['json' => null];

        // Run PHP_CodeSniffer
        $runner = new Runner();
        $runner->config = $config;
        $runner->init();

        // Start buffering the output
        ob_start();
        $runner->runPHPCS();
        $output = ob_get_clean();

        // Get the JSON output
        $report = json_decode($output, true);

        // If there are errors or warnings, fail the test
        if (!empty($report['totals']['errors']) || !empty($report['totals']['warnings'])) {
            $this->fail('Code style violations found. Run `composer fix-style` to fix them.');
        }

        $this->assertTrue(true);
    }
}
