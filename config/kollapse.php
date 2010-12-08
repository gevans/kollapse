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
     * TRUE  - Compresses packages with GZip.
     * FALSE - Disables compression (recommended for development).
     * Default: FALSE
     */
    'gzip_compression' => FALSE,

    /**
     * Driver to use for compression:
     *  - minify
     *  - yui (requires Java)
     * Default: minify
     */
    'driver' => 'minify',

    /**
     * Specify filter classes. These will allow you to use special language extensions
     * and other templating languages for scripts and styles.
     */
    'filters' => array(
    	// 'less',
    	// 'sass',
    ),

    /**
     * Locations in document root where packages are cached and made available.
     */
    'package_paths' => array(
        'javascripts' => DOCROOT.'scripts/',
        'stylesheets' => DOCROOT.'styles/',
    ),

    /**
     * Specify groups of javascripts to be packaged. (Relative to base URL.)
     */
    'javascripts' => array(
        // 'example' => array(
        //     DOCROOT.'javascripts/jquery.js',
        //     DOCROOT.'javascripts/plugins/**/*.js',
        //     DOCROOT.'javascripts/application/*.js',
        // ),
    ),

    /**
     * Specify groups of stylesheets to be packaged. (Relative to base URL.)
     */
    'stylesheets' => array(
        // 'example' => array(
        //     DOCROOT.'stylesheets/reset.css',
        //     DOCROOT.'stylesheets/widgets/**/*.css',
        //     DOCROOT.'stylesheets/styles/*.css',
        // ),
    ),
);
