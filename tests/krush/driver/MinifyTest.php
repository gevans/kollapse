<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Krush minify driver
 *
 * @group krush
 *
 * @package    Unittest
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class DriverMinifyTest extends Kohana_Unittest_TestCase {

	public $config = array(
		'driver' => 'minify',
	);

	/**
	 * @provider scripts_provider
	 */
	public function test_script_compress($compressed, $uncompressed)
	{
		Krush::init($this->config);
		$this->assertSame($compressed, Krush::compress_script($uncompressed));
	}

	/**
	 * @provider styles_provider
	 */
	public function test_style_compress($compressed, $uncompressed)
	{
		Krush::init($this->config);
		$this->assertSame($compressed, Krush::compress_style($uncompressed));
	}

	public function scripts_provider()
	{

	}

	public function styles_provider()
	{
		return array(
		);
	}

}
