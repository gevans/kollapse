<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Handles asset packaging of scripts and stylesheets. Also provides helper functions
 * for including assets in views.
 * @package    Krush
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class Krush_Core
{

	// configuration
	protected static $_config = NULL;

	// driver instance
	protected static $_instance = NULL;

	/**
	 * Stores configuration locally and instantiates compression driver.
	 * @return  void
	 */
	protected static function init(array $config = NULL)
	{
		if ($config === NULL)
		{
			$config = Kohana::config('krush');
		}

		if ( ! isset($config['packaging']))
		{
			$config['packaging'] = (Kohana::$environment != 'development') ? TRUE : FALSE;
		}

		if ( ! isset($config['compression']))
		{
			$config['compression'] = TRUE;
		}

		if ( ! isset($config['driver']))
		{
			$config['driver'] = 'minify';
		}

		if ( ! isset($config['scripts_path']))
		{
			throw new Kohana_Exception('Scripts path not set.');
		}

		if ( ! isset($config['styles_path']))
		{
			throw new Kohana_Exception('Styles path not set.');
		}

		if ( ! isset($config['javascripts']) && ! isset($config['stylesheets']))
		{
			throw new Kohana_Exception('No asset groups defined.');
		}

		self::$_config = $config;

		// instantiate driver
		self::$_instance = new 'Krush_'.$config['driver'];
	}

	/**
	 * Creates script package link.
	 * @param   boolean|string  group(s) of assets
	 * @param   array           additional attributes
	 * @param   boolean         include file timestamp
	 * @return  string
	 * @uses    HTML::script
	 */
	public static function script($group, array $attributes = NULL, $timestamp = TRUE)
	{
		if (self::$_config === NULL)
		{
			self::init();
		}

		if ( ! isset(self::$_config['javascripts'][$group]))
		{
			throw new Kohana_Exception('Javascripts group :group does not exist',
				array($group));
			return;
		}

		$javascripts = self::$_config['javascripts'][$group];

		if ( ! self::$_config['packaging'])
		{
			foreach ($javascripts as $javascript)
			{
				// prepend document root path
				$javascript = DOCROOT . $javascript;

				if ( ! file_exists($javascript))
				{
					throw new Kohana_Exception('Javascript :file does not exist',
						array('file' => $javascript));
					continue;
				}

				if ($timestamp)
				{
					$javascript = $javascript . '?' . filemtime($javascript);
				}

				$return .= HTML::script($javascript, $attributes) . "\n";
			}

			// return links to uncompressed javascripts
			return trim($return);
		}

		$last_update = 0;

		foreach ($group as $file)
		{
			if ( ! file_exists($file))
			{
				throw new Kohana_Exception('Javascript :file does not exist',
					array('file' => $file));
				continue;
			}

			$file = filemtime($file);
			// store most recent last modification timestamp
			$last_update = ($file > $last_update) ? $file : $last_update;
		}

		// generate package filename
		$filename = DOCROOT . self::$_config['package_paths']['javascripts'] . $group . '.css';
		$package_timestamp = filemtime($file);

		if ( ! file_exists($filename) || $last_update > $package_timestamp)
		{
			// (re)package stylesheet group
			$output = self::$_instance->compress_scripts($group);

			if ( ! $output || ! self::save($filename, $output))
			{
				throw new Kohana_Exception('Unable to compress and/or save :asset group: :group',
					array('asset' => 'stylesheet', 'group' => $group));
				return;
			}
		}

		// return link to packaged javascript
		return HTML::script(self::$_config['package_paths']['javascripts'] . $group . '.css?' . $package_timestamp);
	}

	/**
	 * Creates style package link.
	 * @param   boolean|string  group(s) of assets
	 * @param   array           additional attributes
	 * @param   boolean         include file timestamp
	 * @return  string
	 * @uses    HTML::style
	 */
	public static function style($group, $attributes = array(), $timestamp = TRUE)
	{
		if (self::$_config === NULL)
		{
			self::init();
		}

		if ( ! isset(self::$_config['stylesheets'][$group]))
		{
			throw new Kohana_Exception('Stylesheet group :group does not exist',
				array($group));
			return;
		}

		$stylesheets = self::$_config['stylesheets'][$group];

		if ( ! self::$_config['packaging'])
		{
			foreach ($stylesheets as $stylesheet)
			{
				// prepend document root path
				$stylesheet = DOCROOT . $stylesheet;

				if ( ! file_exists($stylesheet))
				{
					throw new Kohana_Exception('Stylesheet :file does not exist',
						array('file' => $stylesheet));
					continue;
				}

				if ($timestamp)
				{
					$stylesheet = $stylesheet . '?' . filemtime($stylesheet);
				}

				$return .= HTML::script($stylesheet, $attributes) . "\n";
			}

			// return links to uncompressed stylesheets
			return trim($return);
		}

		$last_update = 0;

		foreach ($group as $file)
		{
			if ( ! file_exists($file))
			{
				throw new Kohana_Exception('Stylesheet :file does not exist',
					array('file' => $file));
				continue;
			}

			$file = filemtime($file);
			// store most recent last modification timestamp
			$last_update = ($file > $last_update) ? $file : $last_update;
		}

		// generate package filename
		$filename = DOCROOT . self::$_config['package_paths']['stylesheets'] . $group . '.css';
		$package_timestamp = filemtime($file);

		if ( ! file_exists($filename) || $last_update > $package_timestamp)
		{
			// (re)package stylesheet group
			$output = self::$_instance->compress_styles($group);

			if ( ! $output || ! self::save($filename, $output))
			{
				throw new Kohana_Exception('Unable to compress and/or save :asset group: :group',
					array('asset' => 'stylesheet', 'group' => $group));
				return;
			}
		}

		// return link to packaged stylesheet
		return HTML::script(self::$_config['package_paths']['stylesheets'] . $group . '.css?' . $package_timestamp);
	}

	/**
	 * Compresses and saves data to the specified filename.
	 * @return  boolean  success/failure
	 */
	protected static function save($filename = NULL, $output = NULL)
	{
		if ($output === NULL || $group === NULL)
		{
			throw new Kohana_Exception('Asset package filename or data not specified');
			return FALSE;
		}

		if ( ! is_writable(self::$_config['package_paths']['javascripts']))
		{
			throw new Kohana_Exception(':asset path at :path is not writable or does not exist',
				array('asset' => 'Stylesheets', 'path' => self::$_config['package_paths']['javascripts']));
			return FALSE;
		}
		if ( ! self::$_config['package_paths']['stylesheets']))
		{
			throw new Kohana_Exception(':asset path is not writable or does not exist',
				array('asset' => 'Stylesheets', 'path' => self::$_config['package_paths']['stylesheets']));
			return FALSE;
		}

		if (self::$_config['compression'])
		{
			// GZip compress asset
			$output = gzencode($output, 9);

			if ( ! $output)
			{
				throw new Kohana_Exception('Compression of :file failed',
					array('file' => $filename));
				return FALSE;
			}
		}

		if ( ! file_put_contents($filename, $output))
		{
			throw new Kohana_Exception('Unable to save to :file',
				array('file' => $filename));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Combines and compresses scripts.
	 * @param   array    filenames of scripts to compress
	 * @return  string   compressed package
	 * @return  boolean  compression success
	 */
	abstract public static function compress_scripts(array $group);

	/**
	 * Combines and compresses styles.
	 * @param   array   filenames of styles to compress
	 * @return  string  compressed package
	 * @return  boolean compression success
	 */
	abstract public static function compress_styles(array $group);

}
