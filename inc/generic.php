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
