<?php
/**
 * Class SampleTest
 *
 * @package Clinics
 */

use Kunoichi\BootstraPress\Css\Extractor;

/**
 * Sample test case.
 */
class ExtractTest extends WP_UnitTestCase {

	private $extractor = null;
	
	public function setUp() {
		parent::setUp();
		$this->extractor = new Extractor( bootstrapress_dev_css() );
	}
	
	/**
	 * A single example test.
	 */
	public function test_css_properties() {
		$primary_color = $this->extractor->extract( '.text-primary', 'color' );
		$this->assertEquals( '#007bff', $primary_color );
		$alert_bg = $this->extractor->extract( '.alert-primary', 'background-color' );
		$this->assertEquals( '#cce5ff', $alert_bg );
		$alert_border = $this->extractor->extract( '.alert-primary', 'border-color' );
		$this->assertEquals( '#b8daff', $alert_border );
	}
	
	/**
	 * Test theme color extract.
	 */
	public function test_theme_colors() {
		// Theme colors.
		$theme_colors = $this->extractor->get_theme_colors();
		$this->assertNotEmpty( $theme_colors );
		$this->assertEquals( 8, count( $theme_colors ) );
		foreach ( $theme_colors as $theme => $color ) {
			$this->assertRegExp( '/#[a-f0-9]{3,6}/u', $color, "{$theme} color matches.");
		}
		$all_colors = $this->extractor->get_color_palette();
		foreach ( $all_colors as $theme => $color ) {
			$this->assertRegExp( '/#[a-f0-9]{3,6}/u', $color, "{$theme} color palette matches.");
		}
	}
	
	public function test_font_size() {
		$sizes = $this->extractor->get_text_sizes();
		$this->assertNotEmpty( $sizes );
		foreach ( $sizes as $tag => $size ) {
			$this->assertRegExp( '/^[0-9.]+(px|rem|em)$/u', $size, "{$tag} font size matches.");
		}
	}
}
