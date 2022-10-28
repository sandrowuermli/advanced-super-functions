<?php

/*
 * asf_get_locale
 *
 * Returns the current locale.
 *
 * @date    16/12/16
 * @since   5.5.0
 *
 * @param   void
 * @return  string
 */
function asf_get_locale() {
    // Determine local.
    $locale = determine_locale();

    // Fallback to parent language for regions without translation.
    // https://wpastra.com/docs/complete-list-wordpress-locale-codes/
    $langs = [
        'az_TR' => 'az',        // Azerbaijani (Turkey)
        'zh_HK' => 'zh_TW',     // Chinese (Hong Kong)
        'fr_BE' => 'fr_FR',     // French (Belgium)
        'nn_NO' => 'nb_NO',     // Norwegian (Nynorsk)
        'fa_AF' => 'fa_IR',     // Persian (Afghanistan)
        'ru_UA' => 'ru_RU',     // Russian (Ukraine)
    ];
    if ( isset( $langs[ $locale ] ) ) {
        $locale = $langs[ $locale ];
    }

    /**
     * Filters the determined local.
     *
     * @date    8/1/19
     *
     * @param string $locale The local.
     *
     * @since   5.7.10
     *
     */
    return apply_filters( 'asf/get_locale', $locale );
}

/**
 * asf_load_textdomain
 *
 * Loads the plugin's translated strings similar to load_plugin_textdomain().
 *
 * @date    8/1/19
 *
 * @param string $locale The plugin's current locale.
 *
 * @return  void
 * @since   5.7.10
 *
 */
function asf_load_textdomain( $domain = 'asf' ) {

    /**
     * Filters a plugin's locale.
     *
     * @date    8/1/19
     *
     * @param string $locale The plugin's current locale.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     *
     * @since   5.7.10
     *
     */
    $locale = apply_filters( 'plugin_locale', asf_get_locale(), $domain );
    $mofile = $domain . '-' . $locale . '.mo';

    // Load from plugin lang folder.
    return load_textdomain( $domain, asf_get_path( 'lang/' . $mofile ) ); // TODO:SW get path from settings
}
