<?php
/**
 * Advanced Super Functions
 *
 * @package       ASF
 * @author        Sandro Würmli
 *
 * @wordpress-plugin
 * Plugin Name:   Advanced Super Functions
 * Plugin URI:    https://www.advancedcustomfields.com
 * Description:   Helpful tools and functions for WordPress.
 * Version:       0.1.0
 * Author:        Sandro Würmli
 * Author URI:    https://sandro.live/
 * Plugin URI:    https://sandro.live/advanced-super-functions/
 * Text Domain:   asf
 * Domain Path:   /resources/lang
 * Requires PHP:  7.4.27
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ASF' ) ) {

    /**
     * The main ASF class
     */
    class ASF {

        /**
         * The plugin version number.
         *
         * @var string
         */
        public $version = '0.1.0';

        /**
         * The plugin settings array.
         *
         * @var array
         */
        public $settings = [];

        /**
         * The plugin data array.
         *
         * @var array
         */
        public $data = [];

        /**
         * Storage for class instances.
         *
         * @var array
         */
        public $instances = [];

        /**
         * A dummy constructor to ensure ASF is only setup once.
         *
         * @return  void
         */
        public function __construct() {
            // Do nothing.
        }

        /**
         * Sets up the ASF plugin.
         *
         * @return  void
         */
        public function initialize() {
            // Define constants.
            $this->define( 'ASF', true );
            $this->define( 'ASF_PATH', plugin_dir_path( __FILE__ ) );
            $this->define( 'ASF_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'ASF_VERSION', $this->version );
            $this->define( 'ASF_MAJOR_VERSION', 0 );

            // Include utility functions.
            include_once ASF_PATH . 'includes/asf-utility-functions.php';

            // Vendors
            $this->autoload();

            // Define settings.
            $this->update_setting( 'name', __( 'Advanced Super Functions', 'asf' ) );
            $this->update_setting( 'slug', dirname( ASF_BASENAME ) );
            $this->update_setting( 'version', ASF_VERSION );
            $this->update_setting( 'basename', ASF_BASENAME );
            $this->update_setting( 'path', ASF_PATH );
            $this->update_setting( 'file', __FILE__ );
            $this->update_setting( 'url', plugin_dir_url( __FILE__ ) );
            $this->update_setting( 'default_language', '' );
            $this->update_setting( 'current_language', '' );
            $this->update_setting( 'capability', 'manage_options' );
            $this->update_setting( 'docs', 'https://sandro.live/advanced-super-functions' );
            $this->update_setting( 'l10n', true );
            $this->update_setting( 'l10n_textdomain', 'asf' );

            // Include previous API functions.
            asf_include( 'includes/api/api-helpers.php' );

            // Include classes.
            // asf_include( 'includes/classes/lorem.php' ); // TODO:SW classes

            // Include functions.
            asf_include( 'includes/asf-helper-functions.php' );

            // Include core.
            asf_include( 'includes/i18n.php' );

            // Add actions.
            add_action( 'init', [ $this, 'init' ], 5 );
        }

        public function init() {

            // Bail early if called directly from functions.php or plugin file.
            if ( ! did_action( 'plugins_loaded' ) ) {
                return;
            }

            // This function may be called directly from template functions. Bail early if already did this.
            if ( asf_did( 'init' ) ) {
                return;
            }

            // Update url setting. Allows other plugins to modify the URL (force SSL).
            asf_update_setting( 'url', plugin_dir_url( __FILE__ ) );

            // Load textdomain file.
            asf_load_textdomain();

            // Include 3rd party compatiblity.
            // asf_include( 'includes/third-party.php' );
        }

        /*
         * Loads composer autoload
         */
        protected function autoload() {
            if ( ! file_exists( $composer = __DIR__ . '/vendor/autoload.php' ) ) {
                $this->wp_die(
                    __( 'You must run <code>composer install</code> from the Plugin directory.', 'asf' ),
                    __( 'Autoloader not found.', 'asf' )
                );
            }

            require_once $composer;
        }

        /**
         * Defines a constant if doesnt already exist.
         *
         * @param string $name  The constant name.
         * @param mixed  $value The constant value.
         *
         * @return  void
         */
        public function define( $name, $value = true ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * Returns true if a setting exists for this name.
         *
         * @param string $name The setting name.
         *
         * @return  boolean
         */
        public function has_setting( $name ) {
            return isset( $this->settings[ $name ] );
        }

        /**
         * Returns a setting or null if doesn't exist.
         *
         * @param string $name The setting name.
         *
         * @return  mixed
         */
        public function get_setting( $name ) {
            return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
        }

        /**
         * Updates a setting for the given name and value.
         *
         * @param string $name  The setting name.
         * @param mixed  $value The setting value.
         *
         * @return  true
         */
        public function update_setting( $name, $value ) {
            $this->settings[ $name ] = $value;

            return true;
        }

        /**
         * @param string $message
         * @param string $subtitle
         * @param string $title
         *
         * @return void
         */
        public function wp_die( $message, $subtitle = '', $title = '' ) {
            $title   = $title ?: $this->settings['name'] . ' &rsaquo; ' . __( 'Error', 'asf' );
            $footer  = '<a href="' . $this->get_setting( 'docs' ) . '">' . $this->get_setting( 'docs_short' ) . '</a>';
            $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
            wp_die( $message, $title );
        }

        /**
         * Returns data or null if doesn't exist.
         *
         * @date    28/09/13
         *
         * @param string $name The data name.
         *
         * @return  mixed
         * @since   5.0.0
         *
         */
        public function get_data( $name ) {
            return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
        }

        /**
         * Sets data for the given name and value.
         *
         * @date    28/09/13
         *
         * @param string $name  The data name.
         * @param mixed  $value The data value.
         *
         * @return  void
         * @since   5.0.0
         *
         */
        public function set_data( $name, $value ) {
            $this->data[ $name ] = $value;
        }
    }

    /**
     * The main function responsible for returning the one true asf Instance to functions everywhere.
     * Use this function like you would a global variable, except without needing to declare the global.
     *
     * Example: <?php $asf = asf(); ?>
     * @return ASF
     */
    function asf() {
        global $asf;

        // Instantiate only once.
        if ( ! isset( $asf ) ) {
            $asf = new ASF();
            $asf->initialize();
        }

        return $asf;
    }

    // Instantiate.
    asf();

} // class_exists check
