#!/usr/bin/env bash
#
# This file is part of the Valkyrja Framework package.
#
# (c) Melech Mizrachi <melechmizrachi@gmail.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# ---------------------------------------------------------------------------
# Parallel path/branch coverage harness.
#
# xdebug path-coverage instrumentation is ~10x slower than line coverage, so a
# single serial run of the whole suite takes 20+ minutes. This script runs one
# PHPUnit process per component testsuite (see phpunit.path-coverage.xml.dist)
# concurrently, each writing its own serialized coverage, then merges them with
# phpcov. Wall-clock collapses to roughly the slowest single shard.
#
# Because every shard shares the same <source> denominator (all of src) and the
# merged report is the union of all shards, the merged line/branch/path numbers
# are identical to a serial full run -- no shard can drop classes from the total.
#
# Usage:
#   ./path-coverage-shards.sh              Run every shard in parallel, then merge + report.
#   ./path-coverage-shards.sh shard <Name> Run a single shard (used internally and by CI).
#   ./path-coverage-shards.sh merge        Merge existing shard .cov files + report.
#
# Environment:
#   JOBS=<n>          Max concurrent shards (default: CPU count).
#   REQUIRE_LINE=<n>  Fail if merged line coverage % is below n     (default: 100).
#   REQUIRE_BRANCH=<n> Fail if merged branch coverage % is below n  (default: unset = report only).
#   REQUIRE_PATH=<n>  Fail if merged path coverage % is below n     (default: unset = report only).
#   GAPS=1            After reporting, list every file whose branch coverage < 100%.
set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SELF="$DIR/$(basename "${BASH_SOURCE[0]}")"
cd "$DIR"

CONFIG="phpunit.path-coverage.xml.dist"
BUILD="build/path-coverage"
COV_DIR="$BUILD/cov"
CACHE_DIR="$BUILD/cache"
LOG_DIR="$BUILD/logs"
MERGED_COV="$BUILD/merged.cov"

PHP_BIN="${PHP_BIN:-php}"
PHPUNIT="vendor/bin/phpunit"
PHPCOV="vendor/bin/phpcov"

default_jobs() {
    if command -v nproc >/dev/null 2>&1; then
        nproc
    elif command -v sysctl >/dev/null 2>&1; then
        sysctl -n hw.ncpu
    else
        echo 4
    fi
}
JOBS="${JOBS:-$(default_jobs)}"

list_suites() {
    grep -oE 'testsuite name="[^"]+"' "$CONFIG" | sed -E 's/.*name="([^"]+)"/\1/'
}

# Run one shard: full-suite denominator, path coverage, isolated cov + cache +
# storage. The storage dir must be per-shard because TestCase::tearDown() wipes
# it after every test; sharing it lets one shard delete another's fixtures.
run_shard() {
    local suite="$1"
    mkdir -p "$COV_DIR" "$CACHE_DIR/$suite" "$LOG_DIR"

    # Give this shard its own copy of the tests storage skeleton, under the
    # (git-ignored, auto-cleaned) build dir. VALKYRJA_TEST_STORAGE_PATH is
    # relative to the tests directory, so it climbs back out of tests/ into build.
    local storage_abs="$BUILD/storage/$suite"
    local storage_rel="../.github/ci/phpunit/$BUILD/storage/$suite"
    rm -rf "$storage_abs"
    mkdir -p "$storage_abs"
    cp -R ../../../tests/storage/. "$storage_abs/"

    local start end status
    start="$(date +%s)"
    if VALKYRJA_TEST_STORAGE_PATH="$storage_rel" \
        "$PHP_BIN" -d xdebug.mode=coverage -d memory_limit=-1 "$PHPUNIT" \
        -c "$CONFIG" --testsuite "$suite" \
        --path-coverage --coverage-php "$COV_DIR/$suite.cov" \
        --cache-directory "$CACHE_DIR/$suite" \
        --no-progress --display-all-issues > "$LOG_DIR/$suite.log" 2>&1; then
        status="OK  "
    else
        status="FAIL"
    fi
    end="$(date +%s)"
    printf '%s %-12s %4ss\n' "$status" "$suite" "$((end - start))"
    [ "$status" = "OK  " ]
}

# Merge every shard .cov and emit the report / thresholds via report.php.
run_merge() {
    if ! ls "$COV_DIR"/*.cov >/dev/null 2>&1; then
        echo "No shard coverage files in $COV_DIR -- run the shards first." >&2
        exit 1
    fi
    mkdir -p "$LOG_DIR"
    echo "Merging $(ls "$COV_DIR"/*.cov | wc -l | tr -d ' ') shard(s) with phpcov ..."
    "$PHP_BIN" -d memory_limit=-1 "$PHPCOV" merge \
        --php "$MERGED_COV" \
        --clover "$LOG_DIR/path-coverage-clover.xml" \
        "$COV_DIR/" >/dev/null
    REQUIRE_LINE="${REQUIRE_LINE:-100}" \
    REQUIRE_BRANCH="${REQUIRE_BRANCH:-}" \
    REQUIRE_PATH="${REQUIRE_PATH:-}" \
    GAPS="${GAPS:-}" \
        "$PHP_BIN" -d memory_limit=-1 path-coverage-report.php "$MERGED_COV"
}

run_all() {
    rm -rf "$BUILD"
    mkdir -p "$COV_DIR" "$CACHE_DIR" "$LOG_DIR"
    local suites wall_start wall_end rc=0
    suites="$(list_suites)"
    echo "Running $(echo "$suites" | wc -l | tr -d ' ') shards, up to $JOBS in parallel ..."
    echo "----------------------------------------"
    wall_start="$(date +%s)"
    # xargs re-invokes this script in single-shard mode so each shard is its own process.
    if ! echo "$suites" | xargs -P "$JOBS" -I{} "$SELF" shard {}; then
        rc=1
    fi
    wall_end="$(date +%s)"
    echo "----------------------------------------"
    echo "Shard wall-clock: $((wall_end - wall_start))s (JOBS=$JOBS)"
    echo
    run_merge
    if [ "$rc" -ne 0 ]; then
        echo "One or more shards failed -- see $LOG_DIR/<Suite>.log" >&2
        exit 1
    fi
}

case "${1:-all}" in
    shard) run_shard "$2" ;;
    merge) run_merge ;;
    all)   run_all ;;
    *) echo "Usage: $0 [all|shard <Name>|merge]" >&2; exit 2 ;;
esac
