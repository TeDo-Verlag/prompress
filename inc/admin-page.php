<?php
/**
 * Admin Page.
 *
 * @package PromPress
 */

declare( strict_types = 1 );

namespace PromPress;

if ( ! \defined( 'ABSPATH' ) ) {
	die();
}

// Check we are on the admin interface.
if ( ! \is_blog_admin() && ! \is_network_admin() ) {
	return;
}

$plugin_basename = prompress_plugin_basename();

/**
 * Actions
 */
\add_action( is_network_admin() ? 'network_admin_menu' : 'admin_menu', __NAMESPACE__ . '\\register_page' );
if (! is_plugin_active_for_network( $plugin_basename )) {
  \add_action( "plugin_action_links_$plugin_basename", __NAMESPACE__ . '\\settings_link' );
}
\add_action( "network_admin_plugin_action_links_$plugin_basename", __NAMESPACE__ . '\\network_settings_link' );

/**
 * Filters
 */

/**
 * Registers Page
 */
function register_page() {
	\add_submenu_page(
		is_multisite() ? 'settings.php' : 'options-general.php',
		\__( 'PromPress Settings', 'prompress' ),
		\__( 'PromPress', 'prompress' ),
		is_multisite() ? 'manage_network_options' : 'manage_options',
		'options_prompress',
		__NAMESPACE__ . '\\render_page',
	);
}

/**
 * Renders Page
 */
function render_page() {
	?>
	<script>
		var prompress = {
			settings: <?php echo wp_json_encode( get_settings() ); ?>,
			configTemplate: <?php echo wp_json_encode( get_prometheus_config_template() ) . "\n"; ?>
		};
	</script>
	<div id="prompress-plugin-settings"></div>
	<?php
}

/**
 * Add Settings Link to Plugin Screen.
 */
function settings_link( array $links ): array {
	$label = \esc_html__( 'Settings', 'prompress' );
	$slug  = 'options_prompress';

	\array_unshift( $links, "<a href='options-general.php?page=$slug'>$label</a>" );

	return $links;
}

function network_settings_link( array $links ): array {
	$label = \esc_html__( 'Settings', 'prompress' );
	$slug  = 'options_prompress';

	\array_unshift( $links, "<a href='settings.php?page=$slug'>$label</a>" );

	return $links;
}
