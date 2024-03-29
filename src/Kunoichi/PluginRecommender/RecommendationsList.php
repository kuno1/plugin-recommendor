<?php

namespace Kunoichi\PluginRecommender;


use Kunoichi\PluginRecommender;
use Kunoichi\PluginRecommender\Pattern\Singleton;

/**
 * Recommendation List
 *
 * @package PluginRecommender
 */
class RecommendationsList extends Singleton {

	/**
	 * Add recommendation page.
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}

	/**
	 * Add admin menu.
	 */
	public function admin_menu() {
		add_plugins_page( PluginRecommender::get_title(), PluginRecommender::get_menu_title(), 'install_plugins', 'plugins-recommender', [ $this, 'render_screen' ] );
	}

	/**
	 * Enqueue style and JS
	 *
	 * @param string $page
	 */
	public function admin_enqueue_scripts( $page ) {
		if ( 'plugins_page_plugins-recommender' === $page ) {
			$this->enqueue_script( 'plugins-recommender-list', 'dist/js/plugin-list.js', [ 'wp-element', 'wp-api-fetch', 'wp-i18n' ] );
			$this->enqueue_style( 'plugins-recommender-list', 'dist/css/plugin-list.css', [ 'dashicons' ] );
			$plugins = $this->recommender->all();
			usort( $plugins, function( $a, $b ) {
				if ( $a->priority === $b->priority ) {
					return 0;
				} else {
					return ( $a->priority > $b->priority ) ? -1 : 1;
				}
			} );
			wp_localize_script( 'plugins-recommender-list', 'RecommenderList', [
				'plugins' => array_map( function( Plugin $plugin ) {
					return $plugin->name;
				}, $plugins ),
			] );
			wp_set_script_translations( 'plugins-recommender-list', 'pr', $this->recommender->dir . '/languages' );
		}
	}

	/**
	 * Render screen.
	 */
	public function render_screen() {
		?>
		<div class="wrap">
			<h1>
				<span class="dashicons dashicons-plugins-checked"></span>
				<?php echo esc_html( PluginRecommender::get_title() ); ?>
			</h1>
			<?php
			$description = PluginRecommender::get_description();
			if ( $description ) {
				printf( '<p class="description">%s</p>', esc_html( $description ) );
			}
			?>
			<hr />
			<div id="plugin-recommender-list">
			</div>
		</div>
		<?php
	}

	/**
	 * Register REST API
	 */
	public function rest_api_init() {
		register_rest_route( 'kunoichi/v1', 'plugins/recommendation/(?P<namespace>[^/]+)/(?P<slug>.+)', [
			[
				'methods'             => 'GET',
				'permission_callback' => function() {
					return current_user_can( 'install_plugins' );
				},
				'args'                => [
					'namespace' => [
						'required'          => true,
						'validate_callback' => function( $var ) {
							return ! empty( $var );
						},
					],
					'slug'      => [
						'required'          => true,
						'validate_callback' => function( $var ) {
							return ! empty( $var );
						},
					],
				],
				'callback'            => function( \WP_REST_Request $request ) {
					$slug      = $request->get_param( 'slug' );
					$full_slug = sprintf( '%s/%s', $request->get_param( 'namespace' ), $slug );
					$return    = [];
					foreach ( $this->recommender->all() as $plugin ) {
						if ( $full_slug === $plugin->name ) {
							$result = $plugin->retrieve_information();
							return is_wp_error( $result ) ? $result : new \WP_REST_Response( array_merge( $result, [
								'notes'     => $plugin->description,
								'activated' => $plugin->is_active(),
								'priority'  => $plugin->priority,
							] ) );
						}
					}
					return new \WP_Error( 'not_found', __( 'Specified plugin not found.', 'pr' ), [
						'status' => 404,
					] );
				},
			],
		] );
	}
}
