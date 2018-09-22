<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.robertdevore.com/
 * @since      1.2.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <deviodigital@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Metabox example
 */
function add_documentdetails_metaboxes() {
	$screens = apply_filters( 'docupress_details_screens', array( 'docupress' ) );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'docupress_details',
			__( 'Additional Details', 'docupress' ),
			'docupress_document_details',
			$screen,
			'normal',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_documentdetails_metaboxes' );

/**
 * Building the metabox
 */
function docupress_document_details() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="documentdetailsmeta_noncename" id="documentdetailsdmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the thccbd data if its already been entered */
	$path      = get_post_meta( $post->ID, '_docupress_path', true );
	$githuburl = get_post_meta( $post->ID, '_docupress_github_url', true );

	/** Echo out the fields */
	echo '<div class="docupress details">';
	echo '<p>Path <span>(ex: path/to/file.php)</span>:</p>';
	echo '<input type="text" name="_docupress_path" value="' . $thc  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="docupress details">';
	echo '<p>Link <span>(ex: www.github.com/username/link/...)</span>:</p>';
	echo '<input type="text" name="_docupress_github_url" value="' . $githuburl  . '" class="widefat" />';
	echo '</div>';

}

/**
 * Save the Metabox Data
 */
function docupress_save_documentdetails_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['documentdetailsmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$docupress_details['_docupress_path']       = $_POST['_docupress_path'];
	$docupress_details['_docupress_github_url'] = $_POST['_docupress_github_url'];

	/** Add values of $documentdetails_meta as custom fields */

	foreach ( $docupress_details as $key => $value ) { /** Cycle through the $docupress_details array! */
		if ( 'revision' === $post->post_type ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value.
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'docupress_save_documentdetails_meta', 1, 2 ); // save the custom fields.

?>