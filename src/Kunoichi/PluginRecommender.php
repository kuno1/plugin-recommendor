<?php

namespace Kunoichi;


use Kunoichi\PluginRecommender\Plugin;
use Kunoichi\PluginRecommender\RecommendationsList;

/**
 * Plugin recommender object.
 * @package PluginRecommender
 *
 * @property-read int      $count
 * @property-read Plugin[] $uninstalled
 * @property-read string   $url
 * @property-read string   $dir
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
	 *
	 */
	private function __construct() {
		// Load theme text domain.

		// Add plugin Recommendation list.
		RecommendationsList::get_instance();
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
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		switch( $name ) {
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
