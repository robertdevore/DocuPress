<?php

/**
 * The admin-specific functionality of the plugin.
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

// Add Shortcode
function docupress_shortcode( $atts ) {

    // Attributes.
	extract( shortcode_atts(
		array(
            'limit'       => '5',
            'collections' => 'all',
            'order'       => '',
            'viewall'     => 'on',
		),
		$atts,
		'docupress'
	) );

    global $post;
    
    if ( 'all' === $collections ) {
        // Args.
        $args = array(
            'post_type' => 'docupress',
            'showposts' => $limit,
            'orderby'   => $order,
        );
        // Filter args.
        $args = apply_filters( 'docupress_shortcode_query_args', $args );
        // Get results.
        $docupress_articles = new WP_Query( $args );
    } else {
        // Args.
        $args = array(
            'post_type' => 'docupress',
            'showposts' => $limit,
            'orderby'   => $order,
            'tax_query' => array(
                array(
                    'taxonomy' => 'docupress_collections',
                    'field'    => 'slug',
                    'terms'    => $collections
                ),
            ),
        );
        // Filter args.
        $args = apply_filters( 'docupress_shortcode_query_args_collections', $args );
        // Get results.
        $docupress_articles = new WP_Query( $args );
    }

    $docupress_list = '<ul class="docupress-shortcode-list">';

    // Loop through the articles.
    while ( $docupress_articles->have_posts() ) : $docupress_articles->the_post();
        $docupress_list .= '<li>';
        $docupress_list .= '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="docupress-shortcode-link">' . get_the_title( $post->ID ) . '</a>';
        $docupress_list .= '</li>';
    endwhile; // End loop.

    wp_reset_postdata();

    // Website link.
    if ( 'all' !== $collections ) {
        if ( 'on' === $viewall ) {
            $docupress_list .= '<li>';
            $docupress_list .= '<a href="' . get_bloginfo( 'url' ) . '/collections/' . $collections . '">' . __( 'view all', 'docupress' ) . ' &rarr;</a>';
            $docupress_list .= '</li>';
        }
    }

    $docupress_list .= '</ul>';

    return $docupress_list;
}
add_shortcode( 'docupress', 'docupress_shortcode' );
