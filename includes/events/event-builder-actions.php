<?php
/**
 * Contains all event builder related actions
 *
 * @package		MDJM
 * @subpackage	Events
 * @since		1.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Add hidden fields to form.
 *
 * @since   1.5
 * @return  void
 */
function mdjm_event_builder_output_hidden_fields_action()   {
    $step   = mdjm_event_builder_current_step();
    $action = 'event_builder_step';

    if ( mdjm_event_builder_is_last_step( $step ) ) {
        $action = 'event_builder_submit';
    }

    if ( isset( $_POST['mdjm_eb_key'] ) )  {
        $key = $_POST['mdjm_eb_key'];
    } else  {
        $key = wp_generate_password( 12, false );
    }

    $fields = array(
        'step'        => $step,
        'action'      => $action,
        'mdjm_eb_key' => $key
    );

    $fields = apply_filters( 'mdjm_event_builder_hidden_fields', $fields );

    foreach( $fields as $name => $value )    {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
    }
} // mdjm_event_builder_output_hidden_fields_action
add_action( 'mdjm_event_builder_before_buttons', 'mdjm_event_builder_output_hidden_fields_action' );

/**
 * Recreate step cache on package or addon save.
 *
 * @since   1.5
 * @return  void
 */
function mdjm_event_builder_refresh_step_cache_action()  {
    delete_transient( 'mdjm_event_builder_steps' );
} // mdjm_event_builder_refresh_step_cache
add_action( 'mdjm_save_package', 'mdjm_event_builder_refresh_step_cache_action' );
add_action( 'mdjm_delete_package', 'mdjm_event_builder_refresh_step_cache_action' );
add_action( 'mdjm_save_addon', 'mdjm_event_builder_refresh_step_cache_action' );
add_action( 'mdjm_delete_addon', 'mdjm_event_builder_refresh_step_cache_action' );
