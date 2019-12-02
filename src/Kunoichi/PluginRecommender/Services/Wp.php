<?php

namespace Kunoichi\PluginRecommender\Services;


use Kunoichi\PluginRecommender\Pattern\PluginInformation;

/**
 * WordPress Plugin information.
 *
 * @package pr
 */
class Wp extends PluginInformation {

	/**
	 * Get plugin information.
	 *
	 * @return array|\WP_Error
	 */
	protected function get_api_info() {
		$url = sprintf( 'https://api.wordpress.org/plugins/info/1.0/%s.json', $this->slug );
		$info = wp_remote_get( $url );
		if ( is_wp_error( $info ) ) {
			return $info;
		}
		$body = json_decode( $info['body'], true );
		if ( ! $body ) {
			return new \WP_Error( 'invalid_request', __( 'Invalid result format.', 'pr' ), [
				'status' => 500,
			] );
		}
		$result = [];
		foreach ( [
			'title'       => 'name',
			'description' => 'sections',
			'url'         => 'homepage',
			'src'         => sprintf( 'https://ps.w.org/%s/assets/icon-256x256.png', $this->slug ),
				  ] as $key => $prop ) {
			$value = isset( $body[ $prop ] ) ? $body[ $prop ] : '';
			switch ( $key ) {
				case 'description':
					$result[ $key ] = strip_tags( $value[ 'description' ] );
					break;
				case 'src':
					$result[ $key ] = $prop;
					break;
				default:
					$result[ $key ] = $value;
					break;
			}
		}
		return $result;
	}
}
