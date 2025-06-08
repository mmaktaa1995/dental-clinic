<?php

namespace Tests\Unit\StaticAnalysis;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class PhpStanTest extends TestCase
{
    /** @test */
    public function itPassesPhpStanAnalysis(): void
    {
        // Skip this test in CI environments as it's resource-intensive
        if (getenv('CI') === 'true') {
            $this->markTestSkipped('Skipping PHPStan analysis in CI environment');
            return;
        }

        // Create a new process to run PHPStan
        $process = new Process(['./vendor/bin/phpstan', 'analyse', '--no-progress', '--error-format=json'], base_path());
        $process->setTimeout(300); // 5 minutes should be enough
        $process->run();

        // If the process failed, output the error
        if (!$process->isSuccessful()) {
            $output = $process->getErrorOutput() ?: $process->getOutput();
            $this->fail("PHPStan analysis failed:\n" . $output);
        }

        // If we get here, PHPStan analysis passed
        $this->assertTrue($process->isSuccessful());
    }
}
