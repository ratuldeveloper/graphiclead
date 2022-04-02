<?php
$baseDir = dirname(dirname(__FILE__));

return [
    'plugins' => [
        'ApiTokenAuthenticator' => $baseDir . '/vendor/rrd108/api-token-authenticator/',
        'Authentication' => $baseDir . '/vendor/cakephp/authentication/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'Cake/TwigView' => $baseDir . '/vendor/cakephp/twig-view/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'SwaggerBake' => $baseDir . '/vendor/cnizzardini/cakephp-swagger-bake/',
    ],
];
