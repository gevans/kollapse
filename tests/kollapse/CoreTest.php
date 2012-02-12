<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Kollapse core
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
class Kollapse_CoreTest extends Unittest_TestCase {

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
	 * Tests Kollapse::asset_paths()
	 *
	 * @test
	 * @return  void
	 */
	public function test_asset_paths()
	{
		$asset_paths = Kollapse::asset_paths();
		$this->assertInternalType('array', $asset_paths);
	}

	/**
	 * Provides test data for test_find_asset()
	 *
	 * @return  array
	 */
	public function provider_find_asset()
	{
		return array(
			array('octocat.jpg',     Kohana::find_file('tests', 'test_data/assets/images/octocat', 'jpg')),
			array('application.js',  Kohana::find_file('tests', 'test_data/assets/javascripts/application.js', 'coffee')),
			array('application.css', Kohana::find_file('tests', 'test_data/assets/stylesheets/application', 'css')),
		);
	}

	/**
	 * Tests Kollapse::find_asset()
	 *
	 * @test
	 * @dataProvider  provider_find_asset
	 * @param   string  $path      Logical path of asset to find
	 * @param   string  $expected  Expected filesystem path of asset
	 * @return  void
	 */
	public function test_find_asset($logical_path, $expected)
	{
		$asset = Kollapse::find_asset($logical_path);

		$this->assertInstanceOf('Kollapse_Asset', $asset);
		$this->assertSame($expected, $asset->filename());
	}

} // End CoreTest