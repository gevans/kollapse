<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kollapse uses Kohana's cascading filesystem to create
 * an [asset pipeline](http://guides.rubyonrails.org/asset_pipeline.html) that
 * concatenates, minififies, and compresses JavaScript and CSS assets with
 * the ability to interpret other languages such as CoffeeScript and LESS.
 *
 * @package    Kollapse
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT license
 */
class Kohana_Kollapse {

	/**
	 * Has [Kollapse::init] been called?
	 */
	protected static $_init = FALSE;

	/**
	 * @var  array  Asset path cache, used when caching is true in [Kohana::init]
	 */
	protected static $_assets = array();

	/**
	 * @var  boolean  Has the file path cache changed during this execution?  Used internally when when caching is true in [Kohana::init]
	 */
	protected static $_assets_changed = FALSE;

	/**
	 * @var  array  Include paths that are used to find assets
	 */
	protected static $_asset_paths = array();

	/**
	 * @var  array  Configuration
	 */
	protected static $_config = NULL;

	/**
	 * Configuration accessor.
	 *
	 * @param   mixed  $path     Key path string (delimiter separated) or array of keys
	 * @param   mixed  $default  Default value if the path is not set
	 * @return  mixed
	 */
	public static function config($path, $default = NULL)
	{
		if ( ! Kollapse::$_init)
		{
			Kollapse::init();
		}

		return Arr::path(Kollapse::$_config, $path, $default);
	}

	/**
	 * Stores configuration locally and initializes asset paths.
	 *
	 * @return  void
	 */
	public static function init(array $config = NULL)
	{
		if (Kollapse::$_init)
		{
			// Do not allow execution twice
			return;
		}

		// Kollapse is now initialized
		Kollapse::$_init = TRUE;

		if ($config === NULL)
		{
			$config = Kohana::$config->load('kollapse');
		}

		// Store configuration
		Kollapse::$_config = $config;

		// Initialize asset paths
		Kollapse::reset_paths();

		if (Kohana::$caching === TRUE)
		{
			// Load the asset path cache
			Kollapse::$_assets = Kohana::cache('Kollapse::find_asset()');
		}

		// Enable the Kollapse shutdown handler, which caches asset paths.
		register_shutdown_function(array('Kollapse', 'shutdown_handler'));
	}

	/**
	 * Stores asset paths when caching is enabled.
	 *
	 * @return  void
	 */
	public static function shutdown_handler()
	{
		if ( ! Kollapse::$_init)
		{
			// Do not execute when not active
			return;
		}

		if (Kohana::$caching === TRUE AND Kollapse::$_assets_changed === TRUE)
		{
			// Write the asset path cache
			Kohana::cache('Kollapse::find_asset()', Kollapse::$_assets);
		}
	}

	/**
	 * Adds the provided path to the start of the asset paths.
	 *
	 * @param   string  $path
	 * @return  void
	 */
	public static function prepend_path($path)
	{
		if ($valid = Kollapse::valid_asset_path($path))
		{
			array_unshift(Kollapse::$_asset_paths, $valid);
		}
	}

	/**
	 * Adds the provided path to the end of the asset paths.
	 *
	 * @param   string  $path
	 * @return  void
	 */
	public static function append_path($path)
	{
		if ($valid = Kollapse::valid_asset_path($path))
		{
			array_push(Kollapse::$_asset_paths, $valid);
		}
	}

	/**
	 * Checks if the provided path is an existing directory and normalizes it.
	 *
	 * @param   string   $path
	 * @return  string   normalized path
	 * @return  boolean  `FALSE` when non-existent
	 */
	public static function valid_asset_path($path)
	{
		return (is_dir($path)) ? realpath($path).DIRECTORY_SEPARATOR : FALSE;
	}

	/**
	 * Returns the currently registered asset paths.
	 *
	 * @return  array
	 */
	public static function asset_paths()
	{
		return Kollapse::$_asset_paths;
	}

	/**
	 * Clears the currently registered asset paths.
	 *
	 * @return  void
	 */
	public static function clear_paths()
	{
		Kollapse::$_asset_paths = array();
	}

	/**
	 * Clears the asset paths and adds paths for the application, system, and
	 * each registered module.
	 *
	 * @return  void
	 */
	public static function reset_paths()
	{
		Kollapse::clear_paths();

		foreach (Kohana::include_paths() as $path)
		{
			Kollapse::append_path($path.'assets/images');
			Kollapse::append_path($path.'assets/javascripts');
			Kollapse::append_path($path.'assets/stylesheets');
		}
	}

} // End Kollapse

// -- Engines ------------------------------------------------------------------

// Kollapse::register_engine();

// -- Preprocessors ------------------------------------------------------------

// Kollapse::register_preprocessor();

// -- Postprocessors -----------------------------------------------------------

// Kollapse::register_postprocessor();
