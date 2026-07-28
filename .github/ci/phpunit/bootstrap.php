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

use Valkyrja\Application\Directory\Directory;

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/../root-and-suggested/vendor/autoload.php';

require __DIR__ . '/../../../tests/bootstrap.php';

// The parallel path-coverage shards (see path-coverage-shards.sh) all execute in
// this one working copy, so they must not share the storage directory that
// Valkyrja\Tests\Abstract\TestCase::tearDown() wipes after every test. When
// VALKYRJA_TEST_STORAGE_PATH is set (to a path relative to the tests directory),
// point storage at that per-shard directory instead. It is unset everywhere else
// -- normal runs and CI, where each shard is its own checkout, are unaffected.
$shardStoragePath = getenv('VALKYRJA_TEST_STORAGE_PATH');

if (is_string($shardStoragePath) && $shardStoragePath !== '') {
    Directory::$storagePath = $shardStoragePath;
}
