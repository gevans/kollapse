<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CSSMin & JSMin driver for Krush.
 *
 * @package    Krush
 * @category   Driver
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class Kohana_Krush_Minify extends Krush {

	/**
	 * Includes needed libraries (CSSMin & JSMin).
	 */
	protected function __construct($config)
	{
		parent::__construct();

		require_once Kohana::find_file('vendor/cssmin/cssmin');
		require_once Kohana::find_file('vendor/jsmin/jsmin');
	}

}
