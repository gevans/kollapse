<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Handles asset packaging of scripts and stylesheets. Also provides helper functions
 * for including assets in views.
 *
 * @package    Kollapse
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
abstract class Kohana_Kollapse
{

	// configuration
	protected static $config = NULL;

	// driver instance
	protected static $driver;

	// filter instances
	protected static $filters;

	/**
	 * Stores configuration locally and instantiates compression driver.
	 * @return  void
	 */
	protected static function init(array $config = NULL)
	{
		if ($config === NULL)
		{
			$config = Kohana::config('kollapse');
		}

		$config = array_merge(array(
			'packaging' => (Kohana::$environment != 'development') ? TRUE : FALSE,
			'compression' => TRUE,
			'driver' => 'minify',
		), $config);

		if ($config['packaging'] == 'off')
		{
			$config['packaging'] = FALSE;
		}
		elseif ($config['packaging'] == 'always')
		{
			$config['packaging'] = TRUE;
		}

		if ( ! isset($config['package_paths']['javascripts']))
		{
			throw new Kohana_Exception('Javascripts path not set');
		}

		if ( ! isset($config['package_paths']['stylesheets']))
		{
			throw new Kohana_Exception('Stylesheets path not set');
		}

		// save config
		self::$config = $config;

		if (isset($config['filters']))
		{
			foreach ($config['filters'] as $filter)
			{
				$filter = 'Kollapse_Filter_'.$filter;
				// instantiate filter
				self::$filters[$filter] = new $filter;
			}
		}

		$driver = 'Kollapse_'.$config['driver'];
		// instantiate driver
		self::$driver = new $driver($config);

	}

	/**
	 * Stores configuration and makes class publicly uninstantiable.
	 */
	protected function __construct($config)
	{
		self::$config = $config;
	}

	/**
	 * Creates script package link.
	 * @param   array|string  group(s) of assets to link
	 * @param   array         additional attributes
	 * @param   boolean       include file timestamp
	 * @return  string
	 * @uses    HTML::script
	 */
	public static function script($groups, array $attributes = NULL, $timestamp = TRUE)
	{
		if (self::$_config === NULL)
		{
			self::init();
		}

		return HTML::script($group, $attributes);
	}

	/**
	 * Creates stylesheet package link.
	 * @param   string   asset group to link
	 * @param   array    additional attributes
	 * @param   boolean  include file timestamp
	 * @return  string
	 * @uses    HTML::style
	 */
	public static function styles($group, $attributes = array(), $timestamp = TRUE)
	{
		if (self::$_config === NULL)
		{
			self::init();
		}

		return HTML::style($group, $attributes);
	}

}
