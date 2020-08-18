<?php

/**
 * The estimated reading time functionality of the plugin.
 *
 * @link       https://deviodigital.com
 * @since      1.0.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <contact@deviodigital.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add estimated reading time to the content.
 * 
 * @return string
 */
function docupress_the_content_estimated_reading_time( $content ) {
    global $post;
    // Check if this is a DocuPress article.
    if ( is_singular( 'docupress' ) ) {
        return docupress_estimated_reading_time( $post->ID ) . $content;
    } else {
        // Do nothing.
        return $content;
    }
}
add_filter( 'the_content', 'docupress_the_content_estimated_reading_time' );
