<?php defined('SYSPATH') or die('No direct script access.');

return array
(
    /**
     * Options:
     * on     - Packages assets in every environment but development.
     * off    - Never packages.
     * always - Assets are always packaged.
     * Default: on
     */
    'packaging'   => 'on',

    /**
     * Options:
     * TRUE  - Compresses packages.
     * FALSE - Disables compression (recommended for development).
     * Default: off
     */
    'compression' => TRUE,

    /**
     * Driver to use for compression:
     *  - minify
     *  - yui (requires Java)
     * Default: minify
     */
    'driver' => 'minify',

    /**
     * Location in document root where packages are cached and made available. Make
     * sure this is a directory where no other files are stored. (Relative to base URL.)
     */
    'package_paths' => array(
        'javascripts' => 'scripts/',
        'stylesheets' => 'styles/',
    ),

    /**
     * Specify groups of javascripts to be packaged. (Relative to base URL.)
     */
    'javascripts' => array(
        // 'example' => array(
        //     'javascripts/jquery.js',
        //     'javascripts/plugins/**/*.js',
        //     'javascripts/application/*.js',
        // ),
    ),

    /**
     * Specify groups of stylesheets to be packaged. (Relative to base URL.)
     */
    'stylesheets' => array(
        // 'example' => array(
        //     'stylesheets/reset.css',
        //     'stylesheets/widgets/**/*.css',
        //     'stylesheets/styles/*.css',
        // ),
    ),
);
