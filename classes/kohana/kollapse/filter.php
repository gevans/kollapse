<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract Krush filter class. All filters must extend and implement this class.
 *
 * @package    Krush
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
abstract class Kohana_Krush_Filter {

	/**
	 * Define whether a filter can handle javascript, css, or both:
	 *
	 *     // css only
	 *     public $filterable = array('css');
	 *     // javascript only
	 *     public $filterable = array('js');
	 *     // both
	 *     public $filterable = array('css', 'js');
	 *
	 * @var  array  asset types handled
	 */
	public $filterable = array();

	/**
	 * Filter and return data.
	 * @param   string  data to filter
	 * @param   string  data type (js/css)
	 * @return  string  filtered data
	 */
	abstract public function parse($data, $type);

}
