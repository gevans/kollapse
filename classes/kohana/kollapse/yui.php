<?php defined('SYSPATH') or die('No direct script access.');
/**
 * YUI Compressor driver for Kollapse.
 *
 * @package    Kollapse
 * @category   Driver
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class Kohana_Kollapse_YUI extends Kollapse {

	/**
	 * Version of YUI Compressor being used.
	 */
	const YUI_VERSION = '2.4.2';

	/**
	 * @var  string  location of YUI Compressor bin
	 */
	public static $bin = '';

	/**
	 * Sets location of YUI Compressor.
	 */
	protected function __construct($config)
	{
		parent::__construct($config);

		if ( ! $bin = Kohana::find_file('vendor', 'yuicompressor-'.self::YUI_VERSION, 'jar'))
		{
			throw new Kohana_Exception('YUI Compressor :version not found',
				array(':version' => self::YUI_VERSION));
		}
		elseif (!is_executable($bin))
		{
			throw new Kohana_Exception("YUI Compressor at ':location' must be executable",
				array(':location' => $bin));
		}

		self::$bin = $bin;
	}

	protected function optimize($data, $package, $type)
	{
		return $data;
	}

}
