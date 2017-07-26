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

    if ( mdjm_event_builder_is_last_step( $step ) ) {
        $fields['mdjm_honeypot'] = '';

        wp_nonce_field( 'create_event', 'mdjm_nonce' );
    }

    $fields = apply_filters( 'mdjm_event_builder_hidden_fields', $fields );

    foreach( $fields as $name => $value )    {
        echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
    }
} // mdjm_event_builder_output_hidden_fields_action
add_action( 'mdjm_event_builder_before_buttons', 'mdjm_event_builder_output_hidden_fields_action' );

/**
 * Recreate step cache on package, addon, or settings save.
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
add_action( 'update_option_mdjm_settings', 'mdjm_event_builder_refresh_step_cache_action' );

/**
 * Submit the event builder form for review
 *
 * @since   1.5
 * @param   arr     $data   Posted form data
 * @return  void
 */
function mdjm_event_builder_submit_review_action( $data ) {
    mdjm_do_honeypot_check( $data );

    wp_redirect( add_query_arg( array(
        'event_ref' => $data['mdjm_eb_key']
    ), mdjm_get_formatted_url( mdjm_get_option( 'event_builder_page' ) ) ) );
    exit;
} // mdjm_event_builder_submit_review_action
add_action( 'mdjm_event_builder_submit', 'mdjm_event_builder_submit_review_action' );
