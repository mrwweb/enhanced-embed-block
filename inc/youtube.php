<?php
/**
 * YouTube helper functions
 *
 * @package         Enhanced_Embed_Block
 */

namespace EnhancedEmbedBlock;

/**
 * Attempts to replace YouTube iframe in embed block with lite-youtube web component
 *
 * @param string $content embed block's content.
 * @param array  $block block attributes.
 * @return string HTML block content, possibly with modified HTML
 */
function render_youtube_embed( $content, $block ) {
	$video_id = extract_youtube_id_from_uri( $block['attrs']['url'] );
	if ( ! $video_id ) {
		return $content;
	}

	wp_enqueue_script_module( 'lite-youtube' );

	$video_title   = extract_title_from_embed_code( $content );
	$start_time    = extract_start_time_from_youtube_uri( $block['attrs']['url'] );
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
		'<figure class="wp-block-embed wp-block-embed-youtube is-type-video is-provider-youtube %9$s%10$s">
			<div class="wp-block-embed__wrapper">
				<lite-youtube videoid="%1$s" videotitle="%7$s" videoplay="%2$s" videoStartAt="%3$d" posterquality="%4$s" posterloading="lazy"%5$s %11$s disablenoscript>
					<a href="%6$s" class="lite-embed-fallback" target="_blank" rel="noreferrer noopenner">Watch "%7$s" on YouTube</a>
				</lite-youtube>
			</div>
			%8$s
		</figure>',
		esc_attr( $video_id ),
		esc_attr( $play_button ),
		$start_time ? intval( $start_time ) : 0,
		in_array( $poster_quality, array( 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault' ), true ) ? $poster_quality : 'maxresdefault',
		$nocookie ? ' nocookie ' : '',
		esc_url( $block['attrs']['url'] ),
		esc_html( $video_title ),
		$embed_caption,
		esc_attr( $block['attrs']['className'] ),
		alignment_class( $block ),
		aspect_ratio_style( $block )
	);

	return $content;
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
 * Extract the start time parameter "t" from YouTube video URL
 *
 * @param string $uri URL of YouTube video that may or may not contain a start time parameter.
 * @return string|bool The value of the t parameter or false if it isn't present
 */
function extract_start_time_from_youtube_uri( $uri ) {
	$params = wp_parse_url( $uri, PHP_URL_QUERY );
	if ( ! empty( $params ) ) {
		parse_str( $params, $query );
	}
	return $query['t'] ?? false;
}
