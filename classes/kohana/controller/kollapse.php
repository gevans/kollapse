<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kollapse controller.
 *
 * Provides command-line packaging of assets.
 * @package    Kollapse
 * @category   Controller
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class Kohana_Controller_Kollapse extends Controller
{

	/**
	 * Instantiate Kollapse.
	 */
	public function before()
	{
		$kollapse = new Kollapse;
	}

	/**
	 * Build and cache all configured asset groups.
	 */
	public function action_index()
	{

	}

	/**
	 * Return requested scripts.
	 */
	public function action_scripts()
	{

	}

	/**
	 * Return requested styles.
	 */
	public function action_styles()
	{

	}

}
