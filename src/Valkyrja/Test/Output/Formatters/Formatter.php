<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Test\Output\Formatters;

use Valkyrja\Application\Contract\Application;
use Valkyrja\Test\Contract\Test;
use Valkyrja\Test\Exception\AssertFailureException;
use Valkyrja\Test\Output\Formatter as Contract;
use Valkyrja\Test\Output\Results;

use function count;
use function strlen;

use const PHP_EOL;

/**
 * Class Formatter.
 *
 * @author Melech Mizrachi
 */
class Formatter implements Contract
{
    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Valkyrja Unit Testing {$this->getTitleVersion()} by Melech Mizrachi and contributors.";
    }

    /**
     * @inheritDoc
     */
    public function meta(): string
    {
        return "Time: {$this->getMetaTime()}, Memory: {$this->getMetaMemory()}";
    }

    /**
     * @inheritDoc
     */
    public function tests(Results $results): string
    {
        $tests = $results->getTests();

        $testsFormatted = '';

        foreach ($tests as $test) {
            if ($testsFormatted !== '' && (strlen($testsFormatted) % 80) === 0) {
                $testsFormatted .= PHP_EOL;
            }

            $assert = $test->getAssert();
            $result = $this->getTestSuccess();

            if ($assert->getWarnings()) {
                $result = $this->getTestWarning();
            }

            if ($assert->getErrors()) {
                $result = $this->getTestError();
            }

            if ($test->shouldSkip()) {
                $result = $this->getTestSkip();
            }

            $testsFormatted .= $result;
        }

        return $testsFormatted;
    }

    /**
     * @inheritDoc
     */
    public function completed(Results $results): string
    {
        $tests  = $results->getTests();
        $total  = count($tests);
        $failed = 0;

        foreach ($tests as $test) {
            $assert = $test->getAssert();

            if ($assert->getErrors()) {
                $failed++;
            }
        }

        $count         = $total - $failed;
        $percentPassed = ($count / $total) * 100;

        return $this->getCompleted($count, $total, $percentPassed);
    }

    /**
     * @inheritDoc
     */
    public function results(Results $results): string
    {
        $tests  = $results->getTests();
        $status = $this->getResultsOk();

        $totalTests      = count($tests);
        $totalErrors     = 0;
        $totalSkipped    = 0;
        $totalWarnings   = 0;
        $totalAssertions = 0;

        foreach ($tests as $test) {
            $assert = $test->getAssert();

            $totalAssertions += count($assert->getAssertions());

            if ($assert->getWarnings()) {
                $status = $this->getResultsWarning();

                $totalWarnings++;
            }

            if ($assert->getErrors()) {
                $status = $this->getResultsError();

                $totalErrors++;
            }

            if ($test->shouldSkip()) {
                $totalSkipped++;
            }
        }

        return $this->getResults($status, $totalTests, $totalAssertions, $totalSkipped, $totalWarnings, $totalErrors);
    }

    /**
     * @inheritDoc
     */
    public function issues(Results $results): string
    {
        $tests  = $results->getTests();
        $issues = [];
        $num    = 1;

        foreach ($tests as $test) {
            $assert = $test->getAssert();

            if ($errors = $assert->getErrors()) {
                $error    = $errors[0];
                $issues[] = $this->getIssuesIssue($num, $test, $error);

                $num++;
            }
        }

        if (empty($issues)) {
            return $this->getIssuesBlank();
        }

        return $this->getIssues($issues);
    }

    /**
     * @inheritDoc
     */
    public function sectionSpacing(): string
    {
        return PHP_EOL . PHP_EOL;
    }

    /**
     * Get the title's version formatted.
     */
    protected function getTitleVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * Get the meta's time formatted.
     */
    protected function getMetaTime(): string
    {
        return '0';
    }

    /**
     * Get the meta's memory formatted.
     */
    protected function getMetaMemory(): string
    {
        return (string) memory_get_peak_usage();
    }

    /**
     * Get the test's success formatted.
     */
    protected function getTestSuccess(): string
    {
        return '.';
    }

    /**
     * Get the test's warning formatted.
     */
    protected function getTestWarning(): string
    {
        return 'W';
    }

    /**
     * Get the test's error formatted.
     */
    protected function getTestError(): string
    {
        return 'E';
    }

    /**
     * Get the test's skip formatted.
     */
    protected function getTestSkip(): string
    {
        return 'S';
    }

    /**
     * Get the completed full text formatted.
     */
    protected function getCompleted(int $count, int $total, float|int $percentPassed): string
    {
        return "{$this->getCompletedCount($count)} / {$this->getCompletedTotal($total)}"
            . " ({$this->getCompletedPercentPassed($percentPassed)}%) Completed";
    }

    /**
     * Get the completed's count formatted.
     */
    protected function getCompletedCount(int $count): string
    {
        return (string) $count;
    }

    /**
     * Get the completed's total formatted.
     */
    protected function getCompletedTotal(int $total): string
    {
        return (string) $total;
    }

    /**
     * Get the completed's percent passed formatted.
     */
    protected function getCompletedPercentPassed(float|int $percentPassed): string
    {
        return (string) $percentPassed;
    }

    /**
     * Get the results' ok status formatted.
     */
    protected function getResultsOk(): string
    {
        return 'OK';
    }

    /**
     * Get the results' warning status formatted.
     */
    protected function getResultsWarning(): string
    {
        return 'Warning';
    }

    /**
     * Get the results' error status formatted.
     */
    protected function getResultsError(): string
    {
        return 'Error';
    }

    /**
     * Get the results full text formatted.
     */
    protected function getResults(
        string $status,
        int $totalTests,
        int $totalAssertions,
        int $totalSkipped,
        int $totalWarnings,
        int $totalErrors
    ): string {
        return $status
            . ' ('
            . $this->getResultsTotalTests($totalTests)
            . $this->getResultsTotalAssertions($totalAssertions)
            . $this->getResultsTotalErrors($totalErrors)
            . $this->getResultsTotalSkipped($totalSkipped)
            . $this->getResultsTotalWarnings($totalWarnings)
            . ')';
    }

    /**
     * Get the results full text total tests formatted.
     */
    protected function getResultsTotalTests(int $totalTests): string
    {
        return "{$this->getResultsTotalTestsCount($totalTests)} {$this->getResultsTotalTestsGrammar($totalTests)}";
    }

    /**
     * Get the results full text total tests count formatted.
     */
    protected function getResultsTotalTestsCount(int $totalTests): string
    {
        return (string) $totalTests;
    }

    /**
     * Get the results full text total tests grammar formatted.
     */
    protected function getResultsTotalTestsGrammar(int $totalTests): string
    {
        return $totalTests === 1
            ? 'test'
            : 'tests';
    }

    /**
     * Get the results full text total assertions formatted.
     */
    protected function getResultsTotalAssertions(int $totalAssertions): string
    {
        return ", {$this->getResultsTotalAssertionsCount($totalAssertions)} {$this->getResultsTotalAssertionsGrammar($totalAssertions)}";
    }

    /**
     * Get the results full text total assertions count formatted.
     */
    protected function getResultsTotalAssertionsCount(int $totalAssertions): string
    {
        return (string) $totalAssertions;
    }

    /**
     * Get the results full text total assertions grammar formatted.
     */
    protected function getResultsTotalAssertionsGrammar(int $totalAssertions): string
    {
        return $totalAssertions === 1
            ? 'assertion'
            : 'assertions';
    }

    /**
     * Get the results full text total errors formatted.
     */
    protected function getResultsTotalErrors(int $totalErrors): string
    {
        if ($totalErrors <= 0) {
            return '';
        }

        return ", {$this->getResultsTotalErrorsCount($totalErrors)} {$this->getResultsTotalErrorsGrammar($totalErrors)}, ";
    }

    /**
     * Get the results full text total errors count formatted.
     */
    protected function getResultsTotalErrorsCount(int $totalErrors): string
    {
        return (string) $totalErrors;
    }

    /**
     * Get the results full text total errors grammar formatted.
     */
    protected function getResultsTotalErrorsGrammar(int $totalErrors): string
    {
        return $totalErrors === 1
            ? 'error'
            : 'errors';
    }

    /**
     * Get the results full text total warnings formatted.
     */
    protected function getResultsTotalWarnings(int $totalWarnings): string
    {
        if ($totalWarnings <= 0) {
            return '';
        }

        return ", {$this->getResultsTotalWarningsCount($totalWarnings)} {$this->getResultsTotalWarningsGrammar($totalWarnings)}, ";
    }

    /**
     * Get the results full text total warnings count formatted.
     */
    protected function getResultsTotalWarningsCount(int $totalWarnings): string
    {
        return (string) $totalWarnings;
    }

    /**
     * Get the results full text total warnings grammar formatted.
     */
    protected function getResultsTotalWarningsGrammar(int $totalWarnings): string
    {
        return $totalWarnings === 1
            ? 'warning'
            : 'warnings';
    }

    /**
     * Get the results full text total skipped formatted.
     */
    protected function getResultsTotalSkipped(int $totalSkipped): string
    {
        if ($totalSkipped <= 0) {
            return '';
        }

        return ", {$this->getResultsTotalSkippedCount($totalSkipped)} {$this->getResultsTotalSkippedGrammar($totalSkipped)}, ";
    }

    /**
     * Get the results full text total skipped count formatted.
     */
    protected function getResultsTotalSkippedCount(int $totalSkipped): string
    {
        return (string) $totalSkipped;
    }

    /**
     * Get the results full text total skipped grammar formatted.
     */
    protected function getResultsTotalSkippedGrammar(int $totalSkipped): string
    {
        return 'skipped';
    }

    /**
     * Get the issues' issue formatted.
     */
    protected function getIssuesIssue(int $num, Test $test, AssertFailureException $error): string
    {
        return "{$this->getIssuesIssueNum($num)} {$this->getIssuesIssueName($test)}"
            . $this->getIssuesIssueMessage($error)
            . $this->getIssuesIssueTrace($error);
    }

    /**
     * Get the issues' issue num formatted.
     */
    protected function getIssuesIssueNum(int $num): string
    {
        return "{$num})";
    }

    /**
     * Get the issues' issue num formatted.
     */
    protected function getIssuesIssueName(Test $test): string
    {
        return $test->getName();
    }

    /**
     * Get the issues' issue message formatted.
     */
    protected function getIssuesIssueMessage(AssertFailureException $error): string
    {
        return PHP_EOL . $error->getMessage();
    }

    /**
     * Get the issues' issue trace formatted.
     */
    protected function getIssuesIssueTrace(AssertFailureException $error): string
    {
        return PHP_EOL . $error->getTraceAsString();
    }

    /**
     * Get the issues' blank (no errors) formatted.
     */
    protected function getIssuesBlank(): string
    {
        return '';
    }

    /**
     * Get the issues' count full formatted.
     */
    protected function getIssuesCountFull(int $count): string
    {
        return "There {$this->getIssuesCountGrammar($count)} {$this->getIssuesCount($count)} {$this->getIssuesCountErrorGrammar($count)}:";
    }

    /**
     * Get the issues' count formatted.
     */
    protected function getIssuesCount(int $count): string
    {
        return (string) $count;
    }

    /**
     * Get the issues' count grammar formatted.
     */
    protected function getIssuesCountGrammar(int $count): string
    {
        return $count === 1
            ? 'was'
            : 'were';
    }

    /**
     * Get the issues' count error grammar formatted.
     */
    protected function getIssuesCountErrorGrammar(int $count): string
    {
        return $count === 1
            ? 'error'
            : 'errors';
    }

    /**
     * Get the issues full text formatted.
     *
     * @param string[] $issues The issues
     */
    protected function getIssues(array $issues): string
    {
        return $this->sectionSpacing()
            . $this->getIssuesCountFull(count($issues))
            . $this->sectionSpacing()
            . implode($this->sectionSpacing(), $issues);
    }
}
