<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

/*
 * Asserts the coverage floor over the clover report PHPUnit just wrote.
 *
 * PHPUnit has no --fail-under, so the `coverage` script generated a report and nothing read it: a
 * run at 55% passed exactly like one at 100%. This is what makes it a gate.
 *
 * Line coverage only, deliberately. Xdebug reports branch data only under --path-coverage, which is
 * ~10x slower, and clover's <metrics conditionals> is 0 without it — so a "branch" assertion here
 * would silently assert nothing. Branch coverage is checked separately; on `valkyrja` that is
 * `composer phpunit-path-coverage-parallel`, which enforces 100% line *and* branch.
 *
 * The floor is exact — covered === total, not a percentage — so no rounding can sneak a 99.99% past
 * a "100%" gate, and one fully-untested new file cannot hide inside a large covered codebase.
 *
 *   REQUIRE_LINE=<n>  Floor as a percentage (default 100).
 */

$cloverFile = $argv[1] ?? __DIR__ . '/build/logs/clover.xml';

if (! is_file($cloverFile)) {
    fwrite(\STDERR, "Clover report not found: {$cloverFile}\nRun the coverage script first.\n");

    exit(2);
}

$clover = simplexml_load_file($cloverFile);

if ($clover === false) {
    fwrite(\STDERR, "Could not parse clover report: {$cloverFile}\n");

    exit(2);
}

$projectMetrics = $clover->project->metrics ?? null;

if ($projectMetrics === null) {
    fwrite(\STDERR, "Clover report has no project metrics: {$cloverFile}\n");

    exit(2);
}

$total   = (int) $projectMetrics['statements'];
$covered = (int) $projectMetrics['coveredstatements'];

$requirement = getenv('REQUIRE_LINE');
$required    = $requirement === false || $requirement === '' ? 100.0 : (float) $requirement;

// An empty repo (a freshly scaffolded template) has nothing to cover, and that is a pass, not a
// division by zero.
$actual = $total === 0 ? 100.0 : $covered / $total * 100;

$met = $required >= 100.0
    ? $covered === $total
    : $actual + 1.0e-9 >= $required;

printf("Line coverage: %.2f%% (%d/%d)\n", $actual, $covered, $total);

if ($met) {
    printf("PASS  line coverage >= %.2f%%\n", $required);

    exit(0);
}

printf("FAIL  line coverage < %.2f%% (missing %d)\n\n", $required, $total - $covered);

/** @var list<array{name: string, covered: int, total: int}> $gaps */
$gaps = [];

foreach ($clover->project->file as $file) {
    $fileTotal   = (int) $file->metrics['statements'];
    $fileCovered = (int) $file->metrics['coveredstatements'];

    if ($fileTotal > $fileCovered) {
        $gaps[] = [
            'name'    => (string) $file['name'],
            'covered' => $fileCovered,
            'total'   => $fileTotal,
        ];
    }
}

foreach ($clover->project->package as $package) {
    foreach ($package->file as $file) {
        $fileTotal   = (int) $file->metrics['statements'];
        $fileCovered = (int) $file->metrics['coveredstatements'];

        if ($fileTotal > $fileCovered) {
            $gaps[] = [
                'name'    => (string) $file['name'],
                'covered' => $fileCovered,
                'total'   => $fileTotal,
            ];
        }
    }
}

usort($gaps, static fn (array $a, array $b): int => ($b['total'] - $b['covered']) <=> ($a['total'] - $a['covered']));

printf("Files below 100%% line coverage: %d\n", count($gaps));

foreach ($gaps as $gap) {
    printf("  %4d missing  %s\n", $gap['total'] - $gap['covered'], $gap['name']);
}

exit(1);
