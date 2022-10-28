<?php

// Globals.
global $asf_instances;

// Initialize placeholders.
$asf_instances = [];

/**
 * asf_new_instance
 *
 * Creates a new instance of the given class and stores it in the instances data store.
 *
 * @date    9/1/19
 *
 * @param string $class The class name.
 *
 * @return  object The instance.
 * @since   5.7.10
 *
 */
function asf_new_instance( $class = '' ) {
    global $asf_instances;

    return $asf_instances[ $class ] = new $class();
}

/**
 * asf_get_instance
 *
 * Returns an instance for the given class.
 *
 * @date    9/1/19
 *
 * @param string $class The class name.
 *
 * @return  object The instance.
 * @since   5.7.10
 *
 */
function asf_get_instance( $class = '' ) {
    global $asf_instances;
    if ( ! isset( $asf_instances[ $class ] ) ) {
        $asf_instances[ $class ] = new $class();
    }

    return $asf_instances[ $class ];
}

/**
 * asf_get_path
 *
 * Returns the plugin path to a specified file.
 *
 * @date    28/9/13
 *
 * @param string $filename The specified file.
 *
 * @return  string
 * @since   5.0.0
 *
 */
function asf_get_path( $filename = '' ) {
    return ASF_PATH . ltrim( $filename, '/' );
}

/**
 * asf_get_url
 *
 * Returns the plugin url to a specified file.
 * This function also defines the ASF_URL constant.
 *
 * @date    12/12/17
 *
 * @param string $filename The specified file.
 *
 * @return  string
 * @since   5.6.8
 *
 */
function asf_get_url( $filename = '' ) {
    if ( ! defined( 'ASF_URL' ) ) {
        define( 'ASF_URL', asf_get_setting( 'url' ) );
    }

    return ASF_URL . ltrim( $filename, '/' );
}

/*
 * asf_include
 *
 * Includes a file within the ASF plugin.
 *
 * @date    10/3/14
 * @since   5.0.0
 *
 * @param   string $filename The specified file.
 * @return  void
 */
function asf_include( $filename = '' ) {
    $file_path = asf_get_path( $filename );
    if ( file_exists( $file_path ) ) {
        include_once $file_path;
    }
}
