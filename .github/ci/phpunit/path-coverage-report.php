<?php

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * Reports and (optionally) asserts merged path/branch/line coverage.
 *
 * Reads the merged serialized coverage produced by `phpcov merge --php`, prints
 * a line/branch/path summary, and exits non-zero when a required threshold is
 * not met. Thresholds come from the environment so the same script serves both
 * the local "report only" run and a future CI gate:
 *
 *   REQUIRE_LINE    fail if line coverage %   is below this   (empty = skip)
 *   REQUIRE_BRANCH  fail if branch coverage % is below this   (empty = skip)
 *   REQUIRE_PATH    fail if path coverage %   is below this   (empty = skip)
 *   GAPS=1          list every file whose branch coverage is below 100%
 *
 * A threshold of exactly 100 is asserted as executed === executable, so no float
 * rounding can sneak a 99.99% past a "100%" gate.
 */

use SebastianBergmann\CodeCoverage\Node\Builder;
use SebastianBergmann\CodeCoverage\Node\File;
use SebastianBergmann\CodeCoverage\Report\Facade as ReportFacade;
use SebastianBergmann\CodeCoverage\StaticAnalysis\FileAnalyser;
use SebastianBergmann\CodeCoverage\StaticAnalysis\ParsingSourceAnalyser;

require __DIR__ . '/vendor/autoload.php';

$covFile = $argv[1] ?? '';

if ($covFile === '' || ! is_file($covFile)) {
    fwrite(STDERR, "Merged coverage file not found: {$covFile}\n");

    exit(2);
}

/** @var array{basePath: string, codeCoverage: mixed, testResults: mixed} $merged */
$merged = require $covFile;

if (! is_array($merged) || ! isset($merged['codeCoverage'], $merged['testResults'])) {
    fwrite(STDERR, "Not a phpcov merged coverage file: {$covFile}\n");

    exit(2);
}

$summary = ReportFacade::fromSerializedData($merged)->summary();

$metrics = [
    'Lines'    => [$summary->numberOfExecutedLines(), $summary->numberOfExecutableLines()],
    'Branches' => [$summary->numberOfExecutedBranches(), $summary->numberOfExecutableBranches()],
    'Paths'    => [$summary->numberOfExecutedPaths(), $summary->numberOfExecutablePaths()],
];

$percentage = static fn (int $executed, int $executable): float
    => $executable === 0 ? 100.0 : ($executed / $executable) * 100;

echo "=====================================================\n";
echo " Merged path / branch coverage\n";
echo "=====================================================\n";

foreach ($metrics as $label => [$executed, $executable]) {
    printf("  %-9s %7.2f%%  (%d/%d)\n", $label . ':', $percentage($executed, $executable), $executed, $executable);
}

$filesWithoutBranchData = $summary->numberOfFilesWithoutBranchCoverageData();

if ($filesWithoutBranchData > 0) {
    printf("\n  Note: %d file(s) were never loaded, so they have no branch/path data.\n", $filesWithoutBranchData);
}

echo "\n";

$requirements = [
    'Lines'    => getenv('REQUIRE_LINE'),
    'Branches' => getenv('REQUIRE_BRANCH'),
    'Paths'    => getenv('REQUIRE_PATH'),
];

$failed = false;

foreach ($requirements as $label => $requirement) {
    if ($requirement === false || $requirement === '') {
        continue;
    }

    $required                = (float) $requirement;
    [$executed, $executable] = $metrics[$label];
    $actual                  = $percentage($executed, $executable);

    // A "100%" gate is exact: every executable unit must have been executed.
    $met = $required >= 100.0
        ? $executed === $executable
        : $actual + 1.0e-9 >= $required;

    if ($met) {
        printf("  PASS  %s >= %.2f%% (actual %.2f%%)\n", $label, $required, $actual);
    } else {
        printf("  FAIL  %s < %.2f%% (actual %.2f%%, missing %d)\n", $label, $required, $actual, $executable - $executed);

        $failed = true;
    }
}

if (getenv('GAPS') === '1') {
    $report = new Builder(new FileAnalyser(new ParsingSourceAnalyser, false, false))->build(
        $merged['codeCoverage'],
        $merged['testResults'],
        $merged['basePath'],
    );

    /** @var array<int, array{path: string, executed: int, executable: int}> $gaps */
    $gaps = [];

    foreach ($report as $node) {
        if (! $node instanceof File) {
            continue;
        }

        $executable = $node->numberOfExecutableBranches();
        $executed   = $node->numberOfExecutedBranches();

        if ($executable > 0 && $executed < $executable) {
            $gaps[] = [
                'path'       => $node->pathAsString(),
                'executed'   => $executed,
                'executable' => $executable,
            ];
        }
    }

    usort($gaps, static fn (array $a, array $b): int
        => ($b['executable'] - $b['executed']) <=> ($a['executable'] - $a['executed']));

    $totalMissing = array_sum(array_map(static fn (array $gap): int => $gap['executable'] - $gap['executed'], $gaps));

    echo "\n";
    echo "-----------------------------------------------------\n";
    printf(" Files below 100%% branch coverage: %d (%d branches)\n", count($gaps), $totalMissing);
    echo "-----------------------------------------------------\n";

    $basePath = rtrim((string) $merged['basePath'], '/') . '/';

    foreach ($gaps as $gap) {
        $path = str_starts_with($gap['path'], $basePath)
            ? substr($gap['path'], strlen($basePath))
            : $gap['path'];

        printf(
            "  %2d missing  %6.2f%%  %s\n",
            $gap['executable'] - $gap['executed'],
            $percentage($gap['executed'], $gap['executable']),
            $path,
        );
    }
}

exit($failed ? 1 : 0);
