<?php
/**
 * Class SampleTest
 *
 * @package pr
 */


use Kunoichi\PluginRecommender;

/**
 * Sample test case.
 */
class BasicTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_string_set() {
		// Title
		PluginRecommender::set_title( 'aaa' );
		$this->assertEquals( PluginRecommender::get_title(), 'aaa' );
		// Menu
		PluginRecommender::set_menu_title( 'bbb' );
		$this->assertEquals( PluginRecommender::get_menu_title(), 'bbb' );
		// Description.
		PluginRecommender::set_description( 'ccc' );
		$this->assertEquals( PluginRecommender::get_description(), 'ccc' );
	}
}
