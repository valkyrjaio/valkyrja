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
 * Reports and (optionally) asserts merged line/branch coverage.
 *
 * Reads the merged serialized coverage produced by `phpcov merge --php`, prints a
 * line/branch summary, and exits non-zero when a required threshold is not met.
 * Thresholds come from the environment so the same script serves both the local
 * "report only" run and a CI gate:
 *
 *   REQUIRE_LINE    fail if line coverage %   is below this   (empty = skip)
 *   REQUIRE_BRANCH  fail if branch coverage % is below this   (empty = skip)
 *   GAPS=1          list every file whose branch coverage is below 100%
 *
 * A threshold of exactly 100 is asserted as executed === executable, so no float
 * rounding can sneak a 99.99% past a "100%" gate.
 *
 * ---------------------------------------------------------------------------
 * Why branches are recomputed from the shard files instead of read off the merge
 * ---------------------------------------------------------------------------
 *
 * Xdebug identifies a function's basic blocks by opcode offset, and it builds a
 * *different* numbering depending on whether that function ran in the process
 * doing the recording. `phpcov merge` keys branch data on those offsets, so
 * merging a shard that executed a function with one that merely autoloaded it
 * unions two incompatible maps: the executable count inflates and blocks that
 * every shard covered come out unhit.
 *
 * It is not a rounding error. On this suite the merged report claimed 19 missing
 * branches across 4 files, each of which is at 100% in the shard that exercises
 * it — 9 of them in Http/Routing/Processor/Processor.php, whose executable count
 * went from 43 to 52 purely from the merge. It also hits *partially* executing
 * shards, so dropping never-executed records is not enough.
 *
 * The line range Xdebug reports for a block is identical under both numberings,
 * so this script re-keys each block by `line_start-line_end` and ORs the hits
 * across shards. That is exact: the two numberings describe the same blocks.
 *
 * Paths are deliberately NOT reported here. A path is a *sequence of block ids*,
 * so it inherits the unstable numbering, and line ranges cannot stand in — two
 * distinct opcode paths through the same lines collapse onto one key, which
 * silently reports every path as covered. Read paths from a single shard:
 *
 *   composer phpunit-path-coverage-shard Http
 */

use SebastianBergmann\CodeCoverage\Report\Facade as ReportFacade;

require __DIR__ . '/vendor/autoload.php';

$covFile  = $argv[1] ?? '';
$shardDir = $argv[2] ?? '';

if ($covFile === '' || ! is_file($covFile)) {
    fwrite(STDERR, "Merged coverage file not found: {$covFile}\n");

    exit(2);
}

if ($shardDir === '' || ! is_dir($shardDir)) {
    fwrite(STDERR, "Shard coverage directory not found: {$shardDir}\n");

    exit(2);
}

$shardFiles = glob(rtrim($shardDir, '/') . '/*.cov') ?: [];

if ($shardFiles === []) {
    fwrite(STDERR, "No shard coverage files in: {$shardDir}\n");

    exit(2);
}

/**
 * Fold per-shard, per-file unit maps into one map per file.
 *
 * A shard that never executed a file still records a map for it, and that map disagrees with the one
 * recorded by a shard that did — an extra executable line, a different block numbering. Unioning it
 * in inflates the denominator, so a shard's view of a file is only trusted once it has executed
 * something in it. When *no* shard executed the file, one arbitrary view is kept so a genuinely
 * uncovered file still counts against the total instead of vanishing from it.
 *
 * @param array<string, array<string, array<string, bool>>> $perShard shard => file => key => hit
 *
 * @return array<string, array{executed: int, executable: int}>
 */
$fold = static function (array $perShard): array {
    /** @var array<string, array<string, bool>> $merged */
    $merged = [];
    /** @var array<string, array<string, bool>> $untouched */
    $untouched = [];

    foreach ($perShard as $files) {
        foreach ($files as $file => $units) {
            if (array_filter($units) === []) {
                $untouched[$file] ??= $units;

                continue;
            }

            foreach ($units as $key => $hit) {
                $merged[$file][$key] = ($merged[$file][$key] ?? false) || $hit;
            }
        }
    }

    foreach ($untouched as $file => $units) {
        $merged[$file] ??= $units;
    }

    $coverage = [];

    foreach ($merged as $file => $units) {
        $coverage[$file] = [
            'executed'   => count(array_filter($units)),
            'executable' => count($units),
        ];
    }

    return $coverage;
};

/** @var array<string, array<string, array<string, bool>>> $shardBranches */
$shardBranches = [];
/** @var array<string, array<string, array<string, bool>>> $shardLines */
$shardLines = [];

foreach ($shardFiles as $shardFile) {
    /** @var array{codeCoverage: mixed} $shard */
    $shard = require $shardFile;
    $name  = basename($shardFile, '.cov');

    foreach ($shard['codeCoverage']->functionCoverage() as $file => $functions) {
        foreach ($functions as $function => $data) {
            foreach ($data->branches as $branch) {
                // Xdebug's block ids are per-process; the line range it reports for a block is not.
                $key = $branch->line_start . '-' . $branch->line_end . '@' . $function;

                $shardBranches[$name][$file][$key] = $branch->hit !== 0;
            }
        }
    }

    foreach ($shard['codeCoverage']->lineCoverage() as $file => $lines) {
        foreach ($lines as $line => $tests) {
            $shardLines[$name][$file][(string) $line] = $tests !== [] && $tests !== null;
        }
    }
}

$branchCoverage = $fold($shardBranches);
$lineCoverage   = $fold($shardLines);

$branchesExecuted   = array_sum(array_column($branchCoverage, 'executed'));
$branchesExecutable = array_sum(array_column($branchCoverage, 'executable'));
$linesExecuted      = array_sum(array_column($lineCoverage, 'executed'));
$linesExecutable    = array_sum(array_column($lineCoverage, 'executable'));

/** @var array{basePath: string, codeCoverage: mixed, testResults: mixed} $merged */
$merged = require $covFile;

if (! is_array($merged) || ! isset($merged['codeCoverage'], $merged['testResults'])) {
    fwrite(STDERR, "Not a phpcov merged coverage file: {$covFile}\n");

    exit(2);
}

$summary = ReportFacade::fromSerializedData($merged)->summary();

// Both metrics are recomputed from the shard files rather than read off the merge — see the header.
$metrics = [
    'Lines'    => [$linesExecuted, $linesExecutable],
    'Branches' => [$branchesExecuted, $branchesExecutable],
];

$percentage = static fn (int $executed, int $executable): float
    => $executable === 0 ? 100.0 : ($executed / $executable) * 100;

echo "=====================================================\n";
echo " Merged line / branch coverage\n";
echo "=====================================================\n";

foreach ($metrics as $label => [$executed, $executable]) {
    printf("  %-9s %7.2f%%  (%d/%d)\n", $label . ':', $percentage($executed, $executable), $executed, $executable);
}

$filesWithoutBranchData = $summary->numberOfFilesWithoutBranchCoverageData();

if ($filesWithoutBranchData > 0) {
    printf("\n  Note: %d file(s) were never loaded, so they have no branch data.\n", $filesWithoutBranchData);
}

printf("  Branches merged from %d shard(s), re-keyed by line range.\n", count($shardFiles));
echo "  Path coverage is not shown: it cannot be merged across shards. Run a single\n";
echo "  shard for it, e.g. `composer phpunit-path-coverage-shard Http`.\n";

echo "\n";

if (getenv('REQUIRE_PATH') !== false && getenv('REQUIRE_PATH') !== '') {
    echo "  REQUIRE_PATH is ignored: merged path coverage is not a meaningful number.\n\n";
}

$requirements = [
    'Lines'    => getenv('REQUIRE_LINE'),
    'Branches' => getenv('REQUIRE_BRANCH'),
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
    /** @var array<int, array{path: string, executed: int, executable: int}> $gaps */
    $gaps = [];

    // Same re-keyed per-file data the Branches metric is built from, so a file listed here and the
    // summary above can never disagree.
    foreach ($branchCoverage as $file => ['executed' => $executed, 'executable' => $executable]) {
        if ($executable > 0 && $executed < $executable) {
            $gaps[] = [
                'path'       => $file,
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
