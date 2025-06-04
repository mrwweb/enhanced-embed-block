<?php
/**
 * Plugin Name:     Enhanced Embed Block for YouTube & Vimeo
 * Plugin URI:      https://mrwweb.com/wordpress-plugins/enhanced-embed-block/
 * Description:     Make the default YouTube and Vimeo Embed blocks load faster.
 * Author:          Mark Root-Wiley, MRW Web Design
 * Author URI:      https://MRWweb.com
 * Text Domain:     enhanced-embed-block
 * Version:         1.2.0
 * Requires at least: 6.5
 * Requires PHP:    7.4
 * GitHub Plugin URI: mrwweb/enhanced-embed-block
 * Primary Branch:  main
 * License:         GPLv3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package         Enhanced_Embed_Block
 */

namespace EnhancedEmbedBlock;

define( 'EEB_VERSION', '1.2.0' );

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_lite_youtube_component' );
/**
 * Enqueue the JavaScript for the lite-youtube web component
 *
 * @return void
 */
function enqueue_lite_youtube_component() {
	wp_register_script_module(
		'lite-youtube',
		plugins_url( 'vendor/lite-youtube/lite-youtube.js', __FILE__ ),
		array(),
		'1.8.1',
		array( 'async' => true )
	);

	wp_register_script_module(
		'lite-vimeo',
		plugins_url( 'vendor/lite-vimeo/lite-vimeo.js', __FILE__ ),
		array(),
		'1.0.2',
		array( 'async' => true )
	);

	wp_register_style(
		'lite-embed-fallback',
		plugins_url( 'css/lite-embed-fallback.css', __FILE__ ),
		array(),
		EEB_VERSION,
	);
}



/* Pre-2020 Blocks */
add_filter( 'render_block_core-embed/youtube', __NAMESPACE__ . '\replace_embeds_with_web_components', 10, 2 );
/* 2020-onward Block */
add_filter( 'render_block_core/embed', __NAMESPACE__ . '\replace_embeds_with_web_components', 10, 2 );
/**
 * Filter the Embed output and replace it with the web component
 *
 * @param string $content Block html content.
 * @param array  $block The block attributes.
 * @return string HTML for embed block
 */
function replace_embeds_with_web_components( $content, $block ) {

	if ( should_replace_block( $block ) ) {
		return $content;
	}

	wp_enqueue_style( 'lite-embed-fallback' );

	switch ( $block['attrs']['providerNameSlug'] ) {
		case 'youtube':
			$content = render_youtube_embed( $content, $block );
			break;

		case 'vimeo':
			$content = render_vimeo_embed( $content, $block );
			break;
	}

	return $content;
}

/**
 * Runs all checks to determine if we are in the correct context and meet criteria to replace the block
 *
 * @param array  $block The block attributes.
 * @return boolean
 */
function should_replace_block( $block ) {
	return 	! isset( $block['attrs']['url'] ) ||
			! isset( $block['attrs']['providerNameSlug'] ) ||
			is_feed() ||
			! in_array(
				$block['attrs']['providerNameSlug'],
				array( 'youtube', 'vimeo' ),
				true
			);
}

require_once plugin_dir_path( __FILE__ ) . 'inc/generic.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/vimeo.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/youtube.php';
