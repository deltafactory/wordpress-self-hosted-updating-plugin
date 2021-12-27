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
    static function setup() {
        add_filter( 'update_plugins_raw.githubusercontent.com', array( __CLASS__, 'update_plugin_data' ), 10, 4 );
    }

    static function update_plugin_data( $update, $plugin_data, $plugin_file, $locales ) {
        // Is this filter running for this plugin?
        if ( plugin_basename( __FILE__ ) === $plugin_file ) {
            $update_url = $plugin_data['UpdateURI'];

            // This assumes that the data matches the format specified in https://developer.wordpress.org/reference/hooks/update_plugins_hostname/
            $update = json_decode( file_get_contents( $update_url ) );
        }

        return $update;
    }

}

Loader::setup();
