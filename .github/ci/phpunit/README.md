# PHPUnit

The PHPUnit CI tool directory. Like every tool under `.github/ci/`, it carries
its own `composer.json` and vendor tree, and is driven through the root
`composer.json` script shortcuts rather than invoked directly.

| Root script                        | Runs                                          |
|------------------------------------|-----------------------------------------------|
| `phpunit`                          | The suite, no coverage                        |
| `phpunit-coverage`                 | The suite with **line** coverage — the gate   |
| `phpunit-path-coverage`            | The suite with **branch/path** coverage       |
| `phpunit-path-coverage-parallel`   | Branch/path coverage, sharded across processes|
| `phpunit-path-coverage-shard`      | A single named shard                          |
| `phpunit-path-coverage-merge`      | Merge existing shard results and report       |

`phpunit-coverage` is the enforced 100% gate and is unaffected by everything
below.

Parallel branch and path coverage
---------------------------------

`phpunit-path-coverage` runs the whole suite in a single process under Xdebug
path instrumentation. That instrumentation is roughly an order of magnitude
slower than line coverage, so the serial run takes **~32 minutes** and exceeds
Composer's default 300s process timeout (it needs `COMPOSER_PROCESS_TIMEOUT=0`).
In practice that meant branch coverage was never actually looked at.

`phpunit-path-coverage-parallel` runs the same work as one process per
component, concurrently, then merges the results:

```bash
composer phpunit-path-coverage-parallel
```

Wall-clock becomes roughly the slowest single shard instead of the sum of all
of them — measured at **190s versus 1942s serially** (10x, on 10 cores), with
the `Http` shard as the floor at ~171s.

### How it works

1. **`phpunit.path-coverage.xml.dist`** declares one `<testsuite>` per
   top-level component, mirroring `src/Valkyrja/<Component>`, plus `Functional`.
   The suites are disjoint and together cover the whole `tests/` tree, so a run
   with no testsuite filter still collects every test exactly once.
2. **`path-coverage-shards.sh`** runs one PHPUnit process per testsuite, capped
   at the CPU count, each writing its own serialized `.cov`.
3. **`phpcov`** merges the shard files into a single coverage set.
4. **`path-coverage-report.php`** prints merged line/branch/path totals and
   optionally asserts thresholds.

Every shard shares the identical `<source>` denominator (all of `src`), and the
merged report is the union of all shards, so a shard cannot quietly drop classes
from the total.

### Options

All are environment variables:

| Variable           | Effect                                                  |
|--------------------|---------------------------------------------------------|
| `JOBS`             | Max concurrent shards (default: CPU count)              |
| `REQUIRE_LINE`     | Fail if merged line coverage is under this (default 100)|
| `REQUIRE_BRANCH`   | Fail if merged branch coverage is under this            |
| `REQUIRE_PATH`     | Fail if merged path coverage is under this              |
| `GAPS=1`           | List every file below 100% branch coverage, ranked      |

`GAPS=1` is the useful one day to day — it turns "branch coverage is at 99.1%"
into a ranked worklist of files and how many branches each is missing:

```bash
GAPS=1 composer phpunit-path-coverage-parallel
```

A threshold of exactly `100` is asserted as `executed === executable`, so a
rounded 99.99% cannot slip past a "100%" gate.

Shards can also be driven individually, which is what a CI matrix would use —
one job per shard, then a final merge job:

```bash
composer phpunit-path-coverage-shard Http
composer phpunit-path-coverage-merge
```

Everything is written under `build/path-coverage/` (git-ignored): `cov/` holds
the per-shard results, `logs/` the per-shard PHPUnit output, and `merged.cov`
the combined set.

### Two things to know

**Merged totals differ slightly from a serial run.** Merged output reports a
handful more executable lines and branches than the serial run does. This is an
Xdebug artifact, not a regression: when a shard *executes* a `match`, Xdebug
does not report the `match (` line as executable, but a shard that merely
autoloads the file without ever calling the method reports it as executable via
dead-code analysis. Merging unions the two, so the line ends up
executable-but-uncovered.

The consequence is that a merged **line** coverage 100% assertion will fail at
99.97% on this artifact alone, which is why the enforced line gate stays on the
serial `phpunit-coverage` run. The merged data is sound for finding branch gaps.

**Shards need isolated storage.** `Valkyrja\Tests\Abstract\TestCase::tearDown()`
wipes `Directory::storagePath()` after every test. Running shards concurrently
in one working copy would let one shard delete another's fixtures mid-assertion.
`bootstrap.php` therefore honors `VALKYRJA_TEST_STORAGE_PATH`, which the harness
sets per shard. It is unset everywhere else, so normal runs — and CI, where each
matrix shard is its own checkout — behave exactly as before.

### Path coverage is a diagnostic, not a target

Branch coverage is a realistic 100% goal. Path coverage is not: it counts every
distinct route through a function, which grows combinatorially, and the suite
sits around 42%. Treat the path number as information, not a gate.
