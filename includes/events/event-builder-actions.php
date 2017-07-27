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

/**
 * Filter the template name for the event builder.
 *
 * @since	1.5
 * @param	int		$template	The current template name
 * @return	int|str	The template name
 */
function mdjm_event_builder_filter_template_name_action( $template )	{
	if ( $template <= 3 )	{
		return $template;
	}

	if ( 4 == $template && mdjm_event_builder_offer_packages() )	{
		return 'package';
	}

	return 'review';
} // mdjm_event_builder_filter_template_name_action
add_action( 'mdjm_event_builder_template_step', 'mdjm_event_builder_filter_template_name_action' );

/**
 * Purge old event builder transient data from the DB
 *
 * @since   1.5
 * @return  void
 */
function mdjm_event_builder_delete_expired_transients() {
    global $wpdb;

    $max_age = apply_filters( 'mdjm_event_builder_transient_max_age', '1 hour' );
    $expired = strtotime( '-' . $max_age );
    $prefix  = mdjm_event_builder_cache_prefix();

    if ( $expired > time() || $expired < 1 ) {
        return false;
    }

    $expired_cache = $wpdb->get_col(
        $wpdb->prepare( "
                SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name 
                FROM {$wpdb->options} 
                WHERE option_name LIKE %s
                    AND option_value < %s
        ", "_transient_timeout_{$prefix}%%", $expired )
    );

    if ( ! empty( $expired_cache) ) {
        foreach( $expired_cache as $transient ) {
            get_transient( $transient );
        }
    }

} // mdjm_event_builder_delete_expired_transients
add_action( 'mdjm_daily_scheduled_events', 'mdjm_event_builder_delete_expired_transients' );
