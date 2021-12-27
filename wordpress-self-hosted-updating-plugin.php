<?php
/**
 * Plugin Name: Self-hosted Updating Plugin Demo
 * Plugin URI: https://github.com/deltafactory/wordpress-self-hosted-updating-plugin/
 * Description: Minimal example of a plugin with update support outside of the WordPress Repository. It does nothing else.
 * Version: 1.0.2
 * Author: Jeff Brand, Delta Factory
 * Author URI: https://deltafactory.com/
 * Update URI: https://raw.githubusercontent.com/deltafactory/wordpress-self-hosted-updating-plugin/master/update.json
 */

namespace DeltaFactory;

class Loader {
    static $plugin_basename;

    static function setup() {

        self::$plugin_basename = plugin_basename( __FILE__ );

        add_filter( 'update_plugins_raw.githubusercontent.com', array( __CLASS__, 'update_plugin_data' ), 10, 4 );

        add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_meta' ), 10, 2 );
    }

    static function update_plugin_data( $update, $plugin_data, $plugin_file, $locales ) {
        // Is this filter running for this plugin?
        if ( self::$plugin_basename === $plugin_file ) {
            $response = wp_remote_get( $plugin_data['UpdateURI'] );

            // This assumes that the data matches the format specified in https://developer.wordpress.org/reference/hooks/update_plugins_hostname/
            $update = json_decode( wp_remote_retrieve_body( $response ) );
        }

        return $update;
    }

    static function plugin_meta( $plugin_meta, $plugin_file ) {
        if ( self::$plugin_basename == $plugin_file ) {

            // Remove last item if it's the standard TB iframe.
            $lastitem = end( $plugin_meta );
            if ( false !== strpos( $lastitem, 'plugin-install.php' ) ) {
                array_pop( $plugin_meta );
            }

            // Add your own:
        }

        return $plugin_meta;
    }

}

Loader::setup();
