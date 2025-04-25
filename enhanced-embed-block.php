<?php
/**
 * Plugin Name:     Enhanced Embed Block for YouTube
 * Plugin URI:      https://mrwweb.com/wordpress-plugins/enhanced-embed-block/
 * Description:     Enhance the default YouTube Embed block to load faster.
 * Author:          Mark Root-Wiley, MRW Web Design
 * Author URI:      https://MRWweb.com
 * Text Domain:     enhanced-embed-block
 * Version:         1.1.0
 * Requires at least: 6.5
 * Requires PHP:    7.4
 * GitHub Plugin URI: mrwweb/mrw-post-cleanup-utilities
 * Primary Branch:  main
 * License:         GPLv3 or later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package         Enhanced_Embed_Block
 */

namespace EnhancedEmbedBlock;

use WP_HTML_TAG_Processor;

define( 'EEB_VERSION', '1.1.0' );

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
		'1.5.0',
		array( 'in_footer' => true )
	);
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\enqueue_embed_block_style' );
function enqueue_embed_block_style() {
	wp_enqueue_block_style(
		'core/embed',
		array(
			'handle' => 'lite-youtube-custom',
			'src'    => plugins_url( 'css/lite-youtube-custom.css', __FILE__ ),
			'path'   => plugin_dir_path( __FILE__ ) . '/css/lite-youtube-custom.css',
			'ver'    => EEB_VERSION,
		)
	);
}


/* Pre-2020 Blocks */
add_filter( 'render_block_core-embed/youtube', __NAMESPACE__ . '\replace_youtube_embed_with_web_component', 10, 2 );
/* 2020-onward Block */
add_filter( 'render_block_core/embed', __NAMESPACE__ . '\replace_youtube_embed_with_web_component', 10, 2 );
/**
 * Filter the Embed output and replace it with the web component
 *
 * @param string $content Block html content.
 * @param array  $block The block attributes.
 * @return string HTML for embed block
 */
function replace_youtube_embed_with_web_component( $content, $block ) {
	$isValidYouTube = 'youtube' === $block['attrs']['providerNameSlug'] && isset( $block['attrs']['url'] );
	if( ! $isValidYouTube || is_feed() ) {
		return $content;
	}

	$video_id = extract_youtube_id_from_uri( $block['attrs']['url'] );
	if ( ! $video_id ) {
		return $content;
	}

	wp_enqueue_script_module( 'lite-youtube' );

	$video_title   = extract_title_from_embed_code( $content );
	$start_time    = extract_start_time_from_uri( $block['attrs']['url'] );
	$embed_caption = extract_figcaption_from_embed_code( $content );

	/* translators: %s: title from YouTube video */
	$play_button = sprintf( __( 'Play: %s', 'enhanced-embed-block' ), $video_title );

	/**
	 * Filter the poster quality for the YouTube preview thumbnail
	 * 
	 * @since 1.1.0
	 * @param string $quality One of mqdefault, hqdefault, sddefault, or maxresdefault (default)
	 */
	$poster_quality = apply_filters( 'eeb_posterquality', 'maxresdefault' );

	/**
	 * Filter to determine whether to load embed from nocookie YouTube domain
	 * 
	 * @since 1.1.0
	 * @param bool $use_nocookie Whether to use the nocookie domain (default: true)
	 */
	$nocookie = apply_filters( 'eeb_nocookie', true );

	/* Craft the new output: the web component with HTML fallback link */
	$content = sprintf(
		'<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube">
			<div class="wp-block-embed__wrapper">
				<lite-youtube videoid="%1$s" videoplay="%2$s" videoStartAt="%3$d" posterquality="%4$s" posterloading="lazy"%5$s>
					<a href="%6$s" class="lite-youtube-fallback" target="_blank" rel="noreferrer noopenner">Watch "%7$s" on YouTube</a>
				</lite-youtube>
			</div>
			%8$s
		</figure>',
		esc_attr( $video_id ),
		esc_attr( $play_button ),
		$start_time ? intval( $start_time ) : 0,
		in_array( $poster_quality, array( 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault' ), true ) ? $poster_quality : 'maxresdefault',
		$nocookie ? ' nocookie' : '',
		esc_url( $block['attrs']['url'] ),
		esc_html( $video_title ),
		$embed_caption
	);

	return $content;
}

/**
 * Extract the value of the title attribute from HTML that contains an iframe of an existing YouTube embed code
 *
 * @param string $html A block of HTML containing a YouTube iframe.
 * @return string the title attribute valube
 */
function extract_title_from_embed_code( $html ) {
	$processor = new WP_HTML_TAG_Processor( $html );
	$processor->next_tag( 'iframe' );
	$title = $processor->get_attribute( 'title' );

	return $title;
}

/**
 * Extract the figcaption from the embed code
 *
 * @param string $html A block of HTML containing a YouTube iframe.
 * @return string The figcaption OR an empty string
 * 
 * @todo Replace this with HTML Tag Processor, if possible
 */
function extract_figcaption_from_embed_code( $html ) {
	preg_match( '/<figcaption(.*?)<\/figcaption>/s', $html, $match );
	return isset( $match[0] ) ? $match[0] : false;
}

/**
 * Get the YouTube video ID from a YouTube video URL, either the default youtube.com one or the shortened youtu.be one
 *
 * @param string $uri A YouTube video URL.
 * @return string video ID for a YouTube video
 */
function extract_youtube_id_from_uri( $uri ) {
	$host = wp_parse_url( $uri, PHP_URL_HOST );

	/* Handle Shortlinks */
	if ( 'youtu.be' === $host ) {
		return ltrim( wp_parse_url( $uri, PHP_URL_PATH ), '/' );
	}

	$params = wp_parse_url( $uri, PHP_URL_QUERY );
	parse_str( $params, $query );
	return $query['v'] ?? false;
}

/**
 * Extract the start time parameter "t" from  YouTube video URL
 *
 * @param string $uri URL of YouTube video that may or may not contain a start time parameter.
 * @return string|bool The value of the t parameter or false if it isn't present
 */
function extract_start_time_from_uri( $uri ) {
	$params = wp_parse_url( $uri, PHP_URL_QUERY );
	if( !empty($params) ) {
		parse_str( $params, $query );
	}
	return $query['t'] ?? false;
}
