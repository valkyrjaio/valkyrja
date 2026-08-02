# PHPUnit

The PHPUnit CI tool directory. Like every tool under `.github/ci/`, it carries
its own `composer.json` and vendor tree, and is driven through the root
`composer.json` script shortcuts rather than invoked directly.

| Root script                      | Runs                                           |
| -------------------------------- | ---------------------------------------------- |
| `phpunit`                        | The suite, no coverage                         |
| `phpunit-coverage`               | The suite with **line** coverage — the gate    |
| `phpunit-path-coverage`          | The suite with **branch/path** coverage        |
| `phpunit-path-coverage-parallel` | Branch/path coverage, sharded across processes |
| `phpunit-path-coverage-shard`    | A single named shard                           |
| `phpunit-path-coverage-merge`    | Merge existing shard results and report        |

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

| Variable         | Effect                                                   |
| ---------------- | -------------------------------------------------------- |
| `JOBS`           | Max concurrent shards (default: CPU count)               |
| `REQUIRE_LINE`   | Fail if merged line coverage is under this (default 100) |
| `REQUIRE_BRANCH` | Fail if merged branch coverage is under this             |
| `REQUIRE_PATH`   | Fail if merged path coverage is under this               |
| `GAPS=1`         | List every file below 100% branch coverage, ranked       |

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

### Three things to know

**Merged totals overstate the gap — badly, for branches.** Merged output reports
more executable lines and branches than the serial run does. This is an Xdebug
artifact, not a regression: Xdebug builds a different map for a function
depending on whether it actually ran. A shard that _executes_ a `match` does not
report the `match (` line as executable, while a shard that merely autoloads the
file reports it as executable via dead-code analysis. Merging unions the two, so
the entry ends up executable-but-uncovered.

For lines this is small — a merged 100% line assertion fails at 99.97% on the
artifact alone, which is why the enforced line gate stays on the serial
`phpunit-coverage` run. **For branches it is large enough to be misleading**, and
merged branch counts must not be read as a worklist. `Processor.php` is the
worst case: merged claims 52 executable branches and 9 missing, while every
individual shard counts 43 and hits all of them.

Always confirm a branch gap against the file's own shard before writing a test
for it:

```bash
composer phpunit-path-coverage-shard Http
```

**Shards need isolated storage.** `Valkyrja\Tests\Abstract\TestCase::tearDown()`
wipes `Directory::storagePath()` after every test. Running shards concurrently
in one working copy would let one shard delete another's fixtures mid-assertion.
`bootstrap.php` therefore honors `VALKYRJA_TEST_STORAGE_PATH`, which the harness
sets per shard. It is unset everywhere else, so normal runs — and CI, where each
matrix shard is its own checkout — behave exactly as before.

**Some branches can never be executed.** Even within a single process, a handful
of reported branches are unreachable by any test:

- **Process-terminating arms** — `Exiter::exit()` calls `exit($code)`, which ends
  the run, so that arm cannot be recorded.
- **Exhaustive `match` fall-through** — a `match (true)` whose arms already cover
  the parameter's whole union type still carries an implicit `UnhandledMatchError`
  branch that nothing can reach (see `DispatchFactory::fromReflection()`).
- **Traits used by several classes** — a trait is compiled into each using class
  and the copies are conflated into one file entry.
  `Type\Enum\Trait\JsonSerializable` reports 3/3 run alone but 2/3 with the full
  `Type` suite, because a backed enum never runs `return $this->name` and a pure
  enum never runs `return $this->value`. Adding tests _lowers_ this number.

### Neither branch nor path coverage is a 100% target

Path coverage counts every distinct route through a function, which grows
combinatorially; the suite sits around 42%. Treat it as information only.

Branch coverage is far closer to complete and worth driving up, but 100% is not
attainable either, for the reasons above — `REQUIRE_BRANCH=100` can never go
green. Gate it at a threshold below 100 if you want regression protection, and
prefer per-shard numbers over merged ones when deciding what is genuinely
missing.
