<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'jouer' => [
        'path' => './assets/js/jouer.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    'bootstrap/js/dist/alert' => [
        'version' => '4.6.2',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '4.6.2',
        'type' => 'css',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'highlight.js/lib/core' => [
        'version' => '11.9.0',
    ],
    'highlight.js/lib/languages/php' => [
        'version' => '11.9.0',
    ],
    'highlight.js/lib/languages/twig' => [
        'version' => '11.9.0',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    'bootstrap/js/dist/collapse' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/dropdown' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/tab' => [
        'version' => '4.6.2',
    ],
    'bootstrap/js/dist/modal' => [
        'version' => '4.6.2',
    ],
    'highlight.js/styles/github-dark-dimmed.css' => [
        'version' => '11.9.0',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free/css/all.css' => [
        'version' => '6.5.1',
        'type' => 'css',
    ],
    'lato-font/css/lato-font.css' => [
        'version' => '3.0.0',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free/css/v4-shims.css' => [
        'version' => '6.5.1',
        'type' => 'css',
    ],
    'axios' => [
        'version' => '1.6.5',
    ],
];