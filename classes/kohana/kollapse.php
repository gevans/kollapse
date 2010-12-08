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
	protected static $filters = array();

	// file timestamps
	protected static $timestamps;

	/**
	 * Stores configuration locally and instantiates compression driver.
	 * @return  void
	 */
	protected static function init(array $config = NULL)
	{
		if ($config === NULL)
		{
			$config = (array) Kohana::config('kollapse');
		}

		$config = array_merge(array(
			'packaging' => (Kohana::$environment != 'development') ? TRUE : FALSE,
			'gzip_compression' => FALSE,
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
	protected function __construct(array $config)
	{
		self::$config = $config;
	}

	/**
	 * Runs filters on provided data.
	 */
	protected static function filter($data, $type)
	{
		if ($type !== 'javascripts' AND $type !== 'stylesheets')
		{
			throw new Kohana_Exception("Invalid filter type ':type'",
				array(':type' => $type));
		}

		foreach (self::$filters as $filter)
		{
			if (in_array($type, $filter->filterable))
			{
				$data = $filter->parse($data, $type);
			}
		}

		return $data;
	}

	/**
	 * Creates script package link.
	 * @param   array|string  group(s) of assets to link
	 * @param   array         additional attributes
	 * @param   boolean       include file timestamp
	 * @return  string
	 * @uses    HTML::script
	 */
	public static function scripts($groups, array $attributes = NULL, $timestamp = TRUE)
	{
		if (self::$config === NULL)
		{
			self::init();
		}

		if ( ! is_array($groups))
		{
			$groups = array($groups);
		}

		$packages = '';

		if ( ! self::$config['packaging'])
		{
			foreach ($groups as $group)
			{
				foreach (self::$config['javascripts'][$group] as $asset)
				{
					$asset_timestamp = '';

					if ($timestamp)
					{
						$asset_timestamp = self::timestamp($asset);
					}

					$asset = substr_replace($asset, '', 0, strlen(DOCROOT));

					$packages .= HTML::style($asset.'?'.$asset_timestamp)."\n";
				}
			}
		}
		else
		{
			foreach ($groups as $group)
			{
				$packages .= HTML::script(self::package($group, 'javascripts', $timestamp), $attributes)."\n";
			}
		}

		return $packages;
	}

	/**
	 * Creates stylesheet package link.
	 * @param   array|string   asset group(s) to link
	 * @param   array          additional attributes
	 * @param   boolean        include file timestamp
	 * @return  string
	 * @uses    HTML::style
	 */
	public static function styles($groups, $attributes = array(), $timestamp = TRUE)
	{
		if (self::$config === NULL)
		{
			self::init();
		}

		if ( ! is_array($groups))
		{
			$groups = array($groups);
		}

		$packages = '';

		if ( ! self::$config['packaging'])
		{
			foreach ($groups as $group)
			{
				foreach (self::$config['stylesheets'][$group] as $asset)
				{
					$asset_timestamp = '';

					if ($timestamp)
					{
						$asset_timestamp = self::timestamp($asset);
					}

					$asset = substr_replace($asset, '', 0, strlen(DOCROOT));

					$packages .= HTML::style($asset.'?'.$asset_timestamp)."\n";
				}
			}
		}
		else
		{
			foreach ($groups as $group)
			{
				$packages .= HTML::style(self::package($group, 'stylesheets', $timestamp), $attributes)."\n";
			}
		}

		return $packages;
	}

	public static function package($group, $type, $timestamp = TRUE)
	{
		if ( ! isset(self::$config[$type][$group]))
		{
			throw new Kohana_Exception("Asset group ':group' does not exist",
				array(':group' => $group));
		}

		$assets = self::$config[$type][$group];

		switch ($type)
		{
			case 'javascripts':
				$extension = '.js';
			break;
			case 'stylesheets':
				$extension = '.css';
			break;
			default:
				throw new Kohana_Exception("Invalid asset type ':type'",
					array(':type' => $type));
		}

		$package = self::$config['package_paths'][$type].$group;
		$package_url = substr_replace($package, '', 0, strlen(DOCROOT)).$extension;
		$extension = (self::$config['gzip_compression']) ? $extension.'.gz' : $extension;
		$package .= $extension;

		if ( ! file_exists($package) OR (is_file($package) AND self::package_outdated($package, $assets)))
		{
			self::build_package($package, $assets, $type);
		}

		return ($timestamp) ? $package_url.'?'.self::timestamp($package) : $package_url;
	}

	/**
	 * Rebuild an outdated or non-existent package.
	 */
	public static function build_package($package, array $assets, $type)
	{
		if ( ! file_exists($package))
		{
			if ( ! is_writable(dirname($package)))
			{
				throw new Kohana_Exception(":asset directory ':directory' must be writable",
					array(':asset' => ucfirst($type), ':directory' => dirname($package)));
			}
		}
		elseif ( ! is_writable($package))
		{
			throw new Kohana_Exception(":asset package ':package' must be writable",
				array(':asset' => ucfirst($type), ':package' => $package));
		}

		$data = '';

		foreach ($assets as $asset)
		{
			if ( ! is_file($asset))
			{
				throw new Kohana_Exception(":type asset ':file' does not exist",
					array(':type' => ucfirst($type), ':file' => $asset));
			}

			$data .= file_get_contents($asset)."\n";
		}

		$data = self::filter($data, $type);

		$data = self::$driver->optimize($data, $package, $type);

		if (self::$config['gzip_compression'])
		{
			$data = gzencode($data);
		}

		file_put_contents($package, $data);
	}

	abstract protected function optimize($data, $package, $type);

	/**
	 * Check whether the specified package is outdated.
	 */
	public static function package_outdated($package, $assets)
	{
		$outdated = FALSE;
		$latest = 0;

		foreach ($assets as $asset)
		{
			$timestamp = self::timestamp($asset);

			if ($timestamp > $latest)
			{
				// current asset is newest
				$latest = $timestamp;
			}
		}

		if ($latest > self::timestamp($package))
		{
			// package is outdated
			$outdated = TRUE;
		}

		return $outdated;
	}

	/**
	 * Get the last modified timestamp for a file.
	 */
	protected static function timestamp($file)
	{
		if ( ! isset(self::$timestamps[$file]))
		{
			if ( ! file_exists($file))
			{
				throw new Kohana_Exception("Asset ':file' does not exist",
					array(':file' => $file));
			}

			// get & save timestamp
			self::$timestamps[$file] = filemtime($file);
		}

		return self::$timestamps[$file];
	}

}
