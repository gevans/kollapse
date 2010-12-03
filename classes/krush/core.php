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

	/**
	 * @var  boolean  enable asset packaging?
	 */
	public static $packaging = TRUE;

	/**
	 * @var  boolean  enable compression?
	 */
	public static $compression = TRUE;

	/**
	 * @var  string  path where scripts are cached
	 */
	public static $scripts_path = '';

	/**
	 * @var  string  path where styles are cached
	 */
	public static $styles_path = '';

	/**
	 * @var  array  asset package groups
	 */
	public static $groups = array();

	/**
	 * Loads and sets configuration in class properties.
	 * @return  void
	 */
	public static function init()
	{

	}

	/**
	 * Creates cript package link.
	 * @param   boolean|string  group(s) of assets
	 * @param   array           additional attributes
	 * @param   boolean         include file timestamp
	 * @return  string
	 * @uses    HTML::script
	 */
	public static function script($group, array $attributes = NULL, $timestamp = TRUE)
	{

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

	}

}
