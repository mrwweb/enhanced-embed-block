<?php
/**
 * Generic helper functions
 *
 * @package         Enhanced_Embed_Block
 */

namespace EnhancedEmbedBlock;

use WP_HTML_TAG_Processor;

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
 * Generate the alignment class based on the block's alignment attribute
 *
 * @param array $block the block array
 * @return string aligment class or empty string
 */
function alignment_class( $block ) {
	if ( isset( $block['attrs']['align'] ) ) {
		return ' align' . esc_attr( $block['attrs']['align'] ) . ' ';
	}
	return '';
}

/**
 * Set the correct styles to support WP aspect ratio detection
 *
 * @param array $block the block array
 * @return string style attribute or empty string if no aspect ratio class is present
 */
function aspect_ratio_style( $block ) {
	if( isset( $block['attrs']['className'] ) && str_contains( $block['attrs']['className'], 'wp-embed-aspect-' ) ) {
		if( $block['attrs']['providerNameSlug'] === 'youtube' ) {
			//php regex to extract the aspect ratio from the class name
			preg_match( '/wp-embed-aspect-([0-9]+)-([0-9]+)/', $block['attrs']['className'], $matches );
			$style = '--lite-youtube-aspect-ratio:' . $matches[1] . '/' . $matches[2] . ';';
			return ' style="' . $style . '" ';
		}
		
	}
	return '';
}

