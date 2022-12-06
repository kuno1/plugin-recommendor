<?php

namespace Kunoichi\PluginRecommender\Pattern;


use Kunoichi\PluginRecommender;
use Kunoichi\PluginRecommender\Plugin;

/**
 * Singleton pattern
 *
 * @package PluginRecommender
 * @property-read PluginRecommender $recommender
 */
abstract class Singleton {

	/**
	 * Instance store.
	 *
	 * @var static[]
	 */
	private static $instances = [];

	/**
	 * Hiddein constructor.
	 */
	final private function __construct() {
		$this->init();
	}

	/**
	 * Constructor.
	 */
	protected function init() {
		// Do something.
	}

	/**
	 * Instance getter.
	 *
	 * @return static
	 */
	final public static function get_instance() {
		$class_name = get_called_class();
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}
		return self::$instances[ $class_name ];
	}

	/**
	 * Enqueue JS file.
	 *
	 * @param string $handle
	 * @param string $rel_path
	 * @param array  $deps
	 * @param null   $version
	 * @param bool   $footer
	 * @return bool
	 */
	public function enqueue_script( $handle, $rel_path, $deps = [], $version = null, $footer = true ) {
		list( $url, $version ) = $this->get_file_info( $rel_path, $version );
		if ( $url ) {
			wp_enqueue_script( $handle, $url, $deps, $version, $footer );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Enqueue CSS file.
	 *
	 * @param string $handle
	 * @param string $rel_path
	 * @param array  $deps
	 * @param null   $version
	 * @return bool
	 */
	public function enqueue_style( $handle, $rel_path, $deps = [], $version = null ) {
		list( $url, $version ) = $this->get_file_info( $rel_path, $version );
		if ( $url ) {
			wp_enqueue_style( $handle, $url, $deps, $version );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get file information.
	 *
	 * @param string $rel_path
	 * @param null   $version
	 *
	 * @return string[] [$url, $version]
	 */
	private function get_file_info( $rel_path, $version = null ) {
		$rel_path = ltrim( $rel_path, '/' );
		$path     = $this->recommender->dir . '/' . $rel_path;
		if ( file_exists( $path ) ) {
			$url = $this->recommender->url . '/' . $rel_path;
			if ( is_null( $version ) ) {
				$version = filemtime( $path );
			}
			return [ $url, $version ];
		} else {
			return [ '', $version ];
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
			case 'recommender':
				return PluginRecommender::get_instance();
			default:
				return null;
		}
	}
}
