<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Slipway: CI pipeline for the laravel-tagify package
|--------------------------------------------------------------------------
|
| .github/workflows/slipway.yml is GENERATED from this file; never edit it by hand.
| After changing this file run:
|
|   composer slipway:compile      (vendor/bin/testbench slipway:compile)
|
| and commit both files. The `slipway-guard` job in CI fails if they disagree.
|
*/

return [

    'name' => 'CI',

    'drivers' => [
        'github' => [
            'enabled' => true,
            // Resolved with `vendor/bin/testbench slipway:pin`.
            'options' => [
                'pins' => [
                    'actions/cache' => '0057852bfaa89a56745cba8c7296529d2fc39830', // v4
                    'actions/checkout' => '11d5960a326750d5838078e36cf38b85af677262', // v4
                    'shivammathur/setup-php' => 'eb7c497e18156a6bbabfef1d3a82760b9eda3962', // v2
                ],
            ],
        ],
    ],

    'strict' => false,
    'guard' => true,

    // Libraries do not commit composer.lock, so key the cache on composer.json and audit what was installed.
    'lockfile' => false,

    // Run Slipway's commands (drift check, secret scan) through Testbench.
    'artisan' => 'vendor/bin/testbench',

    'php' => ['8.3', '8.4', '8.5'],
    'node' => null,

    'triggers' => [
        'push' => ['main'],
        'tags' => [],
        'pull_request' => true,
        'schedule' => ['0 3 * * 1'],
        'manual' => true,
        'paths_ignore' => [],
    ],

    'concurrency' => ['cancel_superseded' => true],
    'timeouts' => ['default' => 15],
    'cache' => true,
    'env' => [],

    'stages' => [
        'quality' => ['pint', 'larastan', 'secret-scan'],

        'test' => [
            'matrix' => true,
            // The suite runs on Testbench, so skip the Laravel app environment preparation.
            'steps' => ['pest' => ['prepare_env' => false]],
        ],
    ],

    'environments' => [],
    'policies' => [],
    'secrets' => [],

];
