<?php
/**
 * Query Monitor plugin for WordPress
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
 * Domain Path:   /languages
 * Requires PHP: 7.4.27
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

            // Define settings.
            $this->settings = [
                'name'             => __( 'Advanced Super Functions', 'asf' ),
                'slug'             => dirname( ASF_BASENAME ),
                'version'          => ASF_VERSION,
                'basename'         => ASF_BASENAME,
                'path'             => ASF_PATH,
                'file'             => __FILE__,
                'url'              => plugin_dir_url( __FILE__ ),
                'default_language' => '',
                'current_language' => '',
                'capability'       => 'manage_options',
            ];
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
