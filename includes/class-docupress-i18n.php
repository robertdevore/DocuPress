<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    DocuPress
 * @subpackage DocuPress/includes
 * @author     Robert DeVore <contact@deviodigital.com>
 * @link       https://deviodigital.com
 * @since      1.0.0
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    DocuPress
 * @subpackage DocuPress/includes
 * @author     Robert DeVore <contact@deviodigital.com>
 * @link       https://deviodigital.com
 * @since      1.0.0
 */
class DocuPress_i18n {
    /**
     * Load the plugin text domain for translation.
     *
     * @since  1.0.0
     * @return void
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'docupress',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}
