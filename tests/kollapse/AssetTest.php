<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests the Kollapse_Asset class
 *
 * @group kollapse
 *
 * @see        Kollapse
 * @package    Kollapse
 * @category   Tests
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2012 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT license
 */
class Kollapse_AssetTest extends Unittest_TestCase {

	/**
	 * Sets up the filesystem for testing asset paths
	 *
	 * @return  void
	 */
	public static function setupBeforeClass()
	{
		Kollapse::clear_paths();

		$assets_path = realpath(__DIR__.'/../test_data/assets/').DIRECTORY_SEPARATOR;

		Kollapse::prepend_path($assets_path.'images');
		Kollapse::prepend_path($assets_path.'javascripts');
		Kollapse::prepend_path($assets_path.'stylesheets');
	}

	/**
	 * Resets the asset paths
	 *
	 * @return  void
	 */
	public static function teardownAfterClass()
	{
		Kollapse::reset_paths();
	}

	/**
	 * Provides test data for test_content_type()
	 *
	 * @return  array
	 */
	public function provider_content_type()
	{
		return array(
			array('octocat.jpg',        'image/jpeg'),
			array('application.js',     'application/x-javascript'),
			array('less_test.css.less', 'text/css'),
		);
	}

	/**
	 * Tests Kollapse_Asset::content_type()
	 *
	 * @test
	 * @dataProvider  provider_content_type
	 * @param   string  $path      Logical path of asset to find
	 * @param   string  $expected  Expected filesystem path of asset
	 * @return  void
	 */
	public function test_content_type($logical_path, $expected_content_type)
	{
		$asset = Kollapse::find_asset($logical_path);
		$this->assertSame($expected_content_type, $asset->content_type());
	}

	/**
	 * Provides test data for test_format_extension()
	 *
	 * @return  array
	 */
	public function provider_format_extension()
	{
		return array(
			array('octocat.jpg',       'jpg'),
			array('application.js',    'js'),
			array('application.css',   'css'),
			array('required.css.scss', 'css'),
		);
	}

	/**
	 * Tests Kollapse_Asset::format_extension()
	 *
	 * @test
	 * @dataProvider  provider_format_extension
	 * @param   string  $path      Logical path of asset to find
	 * @param   string  $expected  Expected filesystem path of asset
	 * @return  void
	 */
	public function test_format_extension($logical_path, $expected_format_ext)
	{
		$asset = Kollapse::find_asset($logical_path);
		$this->assertSame($expected_format_ext, $asset->format_extension());
	}

} // End AssetTest