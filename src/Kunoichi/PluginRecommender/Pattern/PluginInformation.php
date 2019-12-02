<?php

namespace Kunoichi\PluginRecommender\Pattern;


/**
 * Retrieve Plugin information.
 *
 * @package pr
 */
abstract class PluginInformation {

    protected $slug = '';

    public function __construct( $slug ) {
        $this->slug = $slug;
    }

	/**
	 * Get information or WP_Error
	 *
	 * @return array|\WP_Error
	 */
    abstract protected function get_api_info();

    /**
     * Get information or WP_Error
     *
     * @return array|\WP_Error
     */
    public function retrieve() {
    	$result = $this->get_api_info();
    	if ( is_wp_error( $result ) ) {
    		return $result;
		}
    	$result['install'] = $this->installation_url();
    	return $result;
	}

	/**
	 * Get URL for installation.
	 *
	 * @return string
	 */
    protected function installation_url() {
		return add_query_arg( [
			's'    => $this->slug,
			'tab'  => 'search',
			'type' => 'term'
		], home_url( 'wp-admin/plugin-install.php' ) );
	}
}
