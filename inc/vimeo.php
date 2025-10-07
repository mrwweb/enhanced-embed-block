<?php
/**
 * Vimeo helper functions
 *
 * @package         Enhanced_Embed_Block
 */

namespace EnhancedEmbedBlock;

use WP_HTML_TAG_Processor;

/**
 * Attempts to replace Vimeo iframe in embed block with lite-vimeo web component
 *
 * @param string $content embed block's content.
 * @param array  $block block attributes.
 * @return string HTML block content, possibly with modified HTML
 */
function render_vimeo_embed( $content, $block ) {
	$video_id = extract_vimeo_id( $block['attrs']['url'], $content );

	do_action( 'qm/debug', $block );
	

	if ( ! $video_id ) {
		return $content;
	}

	wp_enqueue_script_module( 'lite-vimeo' );

	$video_title   = extract_title_from_embed_code( $content );
	$start_time    = extract_start_time_from_vimeo_uri( $block['attrs']['url'] );
	$embed_caption = extract_figcaption_from_embed_code( $content );

	/* translators: %s: title from YouTube video */
	$play_button = __( 'Play', 'enhanced-embed-block' );

	/* Craft the new output: the web component with HTML fallback link */
	$content = sprintf(
		'<figure class="wp-block-embed-vimeo wp-block-embed is-type-video is-provider-vimeo %7$s %8$s">
			<div class="wp-block-embed__wrapper">
				<lite-vimeo videoid="%1$s" videotitle="%2$s" videoplay="%3$s" start="%4$ds" %9$s>
					<a href="%5$s" class="lite-embed-fallback" target="_blank" rel="noreferrer noopenner">Watch "%2$s" on Vimeo</a>
				</lite-vimeo>
			</div>
			%6$s
		</figure>',
		esc_attr( $video_id ),
		esc_html( $video_title ),
		esc_html( $play_button ),
		$start_time,
		esc_url( $block['attrs']['url'] ),
		$embed_caption,
		esc_attr( $block['attrs']['className'] ),
		alignment_class( $block ),
		aspect_ratio_style( $block )
	);

	return $content;
}

/**
 * Get the Vimeo video ID from a Vimeo video URL
 *
 * @param string $uri A Vimeo video URL.
 * @param string $content The HTML content of the block.
 * @return string|bool video ID for a Vimeo video or false
 */
function extract_vimeo_id( $uri, $content ) {
	$path = explode( '/', wp_parse_url( $uri, PHP_URL_PATH ) );

	$id = array_pop( $path ) ?? false;

	if ( is_numeric( $id ) ) {
		return $id;
	} else {
		$processor = new WP_HTML_TAG_Processor( $content );
		$processor->next_tag( 'iframe' );
		$uri = $processor->get_attribute( 'src' );

		$path = explode( '/', wp_parse_url( $uri, PHP_URL_PATH ) );

		return array_pop( $path ) ?? false;
	}
}

/**
 * Extract the start time fragment "t" from Vimeo video URL
 *
 * @param string $uri URL of Vimeo video that may or may not contain a start time parameter.
 * @return string|bool The value of the t parameter or false if it isn't present
 */
function extract_start_time_from_vimeo_uri( $uri ) {
	$params = wp_parse_url( $uri, PHP_URL_FRAGMENT );
	if ( ! empty( $params ) ) {
		parse_str( $params, $query );
	}
	return $query['t'] ?? 0;
}
