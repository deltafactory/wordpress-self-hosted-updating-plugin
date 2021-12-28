<?php
/**
 * Plugin Name: Self-hosted Updating Plugin Demo
 * Plugin URI: https://github.com/deltafactory/wordpress-self-hosted-updating-plugin/
 * Description: Minimal example of a plugin with update support outside of the WordPress Repository. It does nothing else.
 * Version: 1.0.4
 * Author: Jeff Brand, Delta Factory
 * Author URI: https://deltafactory.com/
 * Update URI: https://raw.githubusercontent.com/deltafactory/wordpress-self-hosted-updating-plugin/master/update.json
 */

/**
 * Helpful resources:
 *  - https://developer.wordpress.org/reference/hooks/update_plugins_hostname/
 * 
 **/

namespace DeltaFactory\PluginUpdater;

const PLUGIN_SLUG = 'wordpress-self-hosted-updating-plugin';
const PLUGIN_BASENAME = 'wordpress-self-hosted-updating-plugin/wordpress-self-hosted-updating-plugin.php';
const PLUGIN_HOSTNAME = 'raw.githubusercontent.com'; // Changes with Update URI

class Loader {

    static function setup() {
        add_filter( 'update_plugins_' . PLUGIN_HOSTNAME, array( __CLASS__, 'update_plugin_data' ), 10, 4 );
        add_action( 'install_plugins_pre_plugin-information', array( __CLASS__, 'details_popup' ), 9 );
    }

    static function update_plugin_data( $update, $plugin_data, $plugin_file, $locales ) {
        // Is this filter running for this plugin?
        if ( PLUGIN_BASENAME === $plugin_file ) {
            $response = wp_remote_get( $plugin_data['UpdateURI'] );

            // This assumes that the data matches the format specified in https://developer.wordpress.org/reference/hooks/update_plugins_hostname/
            $update = json_decode( wp_remote_retrieve_body( $response ) );
        }

        return $update;
    }

    // Populate pop-up from "View Details" link on plugin/update pages.
    static function details_popup() {
        if ( $_REQUEST['plugin'] != PLUGIN_SLUG ) {
            return;
        }

        // There's a variation that includes &section=changelog when an update is detected.
        /*
        $section = !empty( $_GET['section'] ) ? $_GET['section'] : false;
        if ( $section == 'changelog' ) {
            ...
        }
        */
        echo 'Custom content here.';
        exit();
    }
}

Loader::setup();
