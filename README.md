# Krush

Krush is an asset packaging module for Kohana 3, inspired by
[Jammit](https://github.com/documentcloud/jammit/ "documentcloud's Jammit"), that can
compress and concatenate groups of Javascript and CSS files on-the-fly, or ahead of time.

## Installation

Clone the Git repository into your modules directory:

    $ cd modules/
    $ git clone git://github.com/gevans/krush.git krush

*Or*, clone the repository as a submodule (if you're using Git for your current project):

    $ git submodule add git://github.com/gevans/krush.git modules/krush

As usual, enable the module in your application's `bootstrap.php`:

    Kohana::modules(array(
        // ...
        'krush' => MODPATH.'krush', // Asset packaging
        // ...
    ));


## Configuration

Create `application/config/assets.php` or copy the example from the module's config directory.
In this file, you can configure which assets should be combined and name their groups:

    <?php
    // application/config/assets.php

    return array
    (
        /*
         * Options:
         * on     - Packages assets in every environment but development.
         * off    - Never packages.
         * always - Assets are always packaged.
         * Default: on
         */
        'packaging'   => 'on',

        /*
         * Options:
         * TRUE  - Compresses packages with YUI Compressor.
         * FALSE - Disables compression (recommended for development).
         * Default: off
         */
        'compression' => TRUE,

        /*
         * The URI paths where packages are cached and made available. Make sure you
         * make this a directory where no other files are stored. Otherwise, you may
         * overwrite something.
         */
        'package_path' => array(
            'javascripts' => '/scripts/', // Ends up being http://localhost/scripts/
            'stylesheets' => '/styles/',  // Ends up being http://localhost/styles/
        ),

        /*
         * Specify groups of javascripts to be packaged.
         */
        'javascripts' => array(
            'common' => array(
                DOCROOT.'javascripts/jquery.js',
                // Include all recursively, one level deep
                DOCROOT.'javascripts/plugins/**/*.js',
                DOCROOT.'javascripts/application.js',
            ),
        ),

        /*
         * Specify groups of stylesheets to be packaged.
         */
        'stylesheets' => array(
            'common' => array(
                DOCROOT.'stylesheets/reset.css',
                DOCROOT.'stylesheets/widgets/*.css',
                DOCROOT.'stylesheets/style.css',
            ),
            'workspace' => array(
                DOCROOT.'stylesheets/workspace.css',
            ),
        ),
    );

## Usage

### Embedding

To embed your scripts and styles, use the Krush helper in your views:

    // Include the 'common' group
    echo Krush::scripts('common');

    // Specify multiple groups and attributes
    echo Krush::styles(array('common', 'workspace'), array('media' => 'screen'));

This will create HTML tags to include the specified groups, as well as allowing you to
assign specific attributes in the generated HTML. Timestamps are appended to the URL,
allowing you to send expiration dates far in the future, while causing browsers to fetch
the latest copy of your packaged assets when they're updated:

    <link href="/styles/common.css?1291393143" media="screen" rel="stylesheet "type="text/css" />

### Precaching & Packaging

To immediately package and cache all of your assets, you can use the command-line to
call Krush:

    $ php /path/to/index.php --uri=krush

The above command will read your configured groups and save them all at your package
path.

## Further Reading

For more documentation, enable the [userguide](https://github.com/kohana/userguide/)
module and visit [http://localhost/guide/krush.getting_started](http://localhost/guide/krush.getting_started).
