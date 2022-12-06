<?php

namespace Kunoichi;


use Kunoichi\PluginRecommender\Plugin;
use Kunoichi\PluginRecommender\RecommendationsList;

/**
 * Plugin recommender object.
 * @package PluginRecommender
 *
 * @property-read int      $count                     Number of plugins.
 * @property-read Plugin[] $uninstalled               Uninstalled plugin list.
 * @property-read string   $url                       URL of this repo.
 * @property-read string   $dir                       Plugin directory.
 * @method static string   get_title()                Get title.
 * @method static string   get_menu_title()           Get menu title.
 * @method static string   get_description()          Get description.
 * @method static void     set_title( $string )       Set title.
 * @method static void     set_menu_title( $string )  Set menu title.
 * @method static void     set_description( $string ) Set description.
 */
class PluginRecommender {

	/**
	 * @var self
	 */
	private static $instance = null;

	/**
	 * @var Plugin[]
	 */
	private $plugins = [];

	/**
	 * @var string Menu title.
	 */
	private $title = '';

	/**
	 * @var string Menu title
	 */
	private $menu_title = '';

	/**
	 * @var string Description
	 */
	private $description = '';

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Load theme text domain.
		load_textdomain( 'pr', $this->dir . sprintf( '/languages/%s.mo', get_locale() ) );
		// Add plugin Recommendation list.
		RecommendationsList::get_instance();
		$this->title      = __( 'Recommended Plugins', 'pr' );
		$this->menu_title = __( 'Recommended Plugins', 'pr' );
	}

	/**
	 * Enable plugin recommendation.
	 *
	 * @retun self
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add plugin.
	 *
	 * @param array $setting
	 */
	public static function add( $setting ) {
		$plugin = new Plugin( $setting );
		if ( $plugin->valid ) {
			self::get_instance()->plugins[ $plugin->name ] = $plugin;
		}
	}

	/**
	 * Bulk add plugins.
	 *
	 * @param array $settings
	 */
	public static function bulk_add( $settings ) {
		foreach ( $settings as $setting ) {
			self::add( $setting );
		}
	}

	/**
	 * Load setting from JSON file.
	 *
	 * @param string $setting_json JSON file path.
	 */
	public static function load( $setting_json ) {
		if ( ! file_exists( $setting_json ) ) {
			return;
		}
		$json = json_decode( file_get_contents( $setting_json ), true );
		if ( ! $json ) {
			return;
		}
		foreach ( $json as $plugin ) {
			self::add( $plugin );
		}
	}

	/**
	 * Automatically load setting files.
	 */
	public static function autoload() {
		$dir = [ get_template_directory() ];
		if ( get_template_directory() !== get_stylesheet_directory() ) {
			$dir[] = get_stylesheet_directory();
		}
		foreach ( $dir as $d ) {
			$path = $d . '/recommendations.json';
			if ( file_exists( $path ) ) {
				self::load( $path );
			}
		}
	}

	/**
	 * Get all plugins.
	 *
	 * @return Plugin[]
	 */
	public function all() {
		return array_values( $this->plugins );
	}

	/**
	 * Magic method.
	 *
	 * @param string $name      Method name.
	 * @param array  $arguments Arguments.
	 *
	 * @return mixed|void
	 */
	public static function __callStatic( $name, $arguments = [] ) {
		if ( ! preg_match( '/^(get|set)_(title|menu_title|description)$/u', $name, $matches ) ) {
			return;
		}
		list( $all, $get_or_set, $property ) = $matches;
		$instance = self::get_instance();
		if ( 'set' === $get_or_set ) {
			list( $value ) = $arguments;
			$instance->{$property} = $value;
		} else {
			return $instance->{$property};
		}
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'count':
				return count( $this->plugins );
			case 'uninstalled':
				return array_filter( $this->plugins, function ( Plugin $plugin ) {
					return $plugin->is_installed;
				} );
			case 'dir':
				return dirname( dirname( __DIR__ ) );
			case 'url':
				return str_replace( ABSPATH, home_url( '/' ), $this->dir );
			default:
				return null;
		}
	}
}
