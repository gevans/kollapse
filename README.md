# Krush

Krush is an asset packaging module for Kohana 3, inspired by
[Jammit](https://github.com/documentcloud/jammit/ "documentcloud's Jammit"), that can
combine and compress groups of Javascript and CSS files on-the-fly, or ahead of time.

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

Copy `config/krush.php` to your `application/config/` directory. You can then set
groups of assets and tweak the configuration to your liking.

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
