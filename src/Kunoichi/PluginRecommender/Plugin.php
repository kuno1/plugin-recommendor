<?php

namespace Kunoichi\PluginRecommender;


use Kunoichi\PluginRecommender\Pattern\PluginInformation;

/**
 * Plugin instance
 *
 * @package PluginRecommendor
 * @property-read bool   $valid
 * @property-read string $name
 * @property-read string $source
 * @property-read string $slug
 * @property-read int    $priority
 * @property-read string $description
 */
class Plugin {

	/**
	 * @var array
	 */
	private $setting;

	/**
	 * Plugin constructor.
	 *
	 * @param array $setting
	 */
	public function __construct( $setting ) {
		$filtered = [];
		foreach ( [
			'source'      => 'wp',
			'slug'        => '',
			'description' => '',
			'priority'    => 0,
		] as $key => $default ) {
			$filtered[ $key ] = isset( $setting[ $key ] ) ? $setting[ $key ] : $default;
		}
		$this->setting = $filtered;
	}

	/**
	 * Plugin full name.
	 *
	 * @return string
	 */
	private function get_plugin_name() {
		return sprintf( '%s/%s', $this->source, $this->slug );
	}

	/**
	 * Try to get information.
	 *
	 * @return array|\WP_Error
	 */
	public function retrieve_information() {
		$class_name = sprintf( 'Kunoichi\PluginRecommender\Services\%s', ucfirst( $this->source ) );
		if ( class_exists( $class_name ) ) {
			/** @var PluginInformation $recommendor */
			$recommendor = new $class_name( $this->slug );
			$result      = $recommendor->retrieve();
		} else {
			$result = new \WP_Error( 'no_information', __( 'Invalid namespace', 'pr' ), [
				'status' => 400,
			] );
		}
		return apply_filters( 'plugin_recommender_information', $result, $this );
	}

	/**
	 * Detect if plugin is active.
	 *
	 * @return bool
	 */
	public function is_active() {
		foreach ( (array) get_option( 'active_plugins', [] ) as $files ) {
			list( $plugin_base ) = explode( '/', $files );
			if ( $this->slug === $plugin_base ) {
				return true;
			}
		}
		if ( ! is_multisite() ) {
			return false;
		}
		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( isset( $plugins[ $this->slug ] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'valid':
				return $this->slug && $this->source;
			case 'name':
				return $this->get_plugin_name();
			case 'priority':
				return max( min( 100, absint( $this->setting['priority'] ) ), 0 );
			default:
				return isset( $this->setting[ $name ] ) ? $this->setting[ $name ] : null;
		}
	}
}
