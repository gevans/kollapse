<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Krush YUI Compressor driver
 *
 * @group krush
 *
 * @package    Unittest
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2010 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php MIT license
 */
class YUITest extends Kohana_Unittest_TestCase
{

	public function test_binary_exists()
	{
		$this->assertFileExists(Kohana::find_file('vendor', 'yuicompressor-'.Krush_YUI::YUI_VERSION, 'jar'));
	}

	public function test_binary_executable()
	{
		$this->assertEquals(is_executable(Kohana::find_file('vendor', 'yuicompressor-'.Krush_YUI::YUI_VERSION, 'jar')), TRUE);
	}

}
