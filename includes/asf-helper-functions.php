<?php
/**
 * Returns true if ACF already did an event.
 *
 * @param string $name The name of the event.
 *
 * @return  bool
 */
function asf_did( $name ) {

    // Return true if already did the event (preventing event).
    if ( asf_get_data( "asf_did_$name" ) ) {
        return true;

        // Otherwise, update store and return false (allowing event).
    } else {
        asf_set_data( "asf_did_$name", true );

        return false;
    }
}
