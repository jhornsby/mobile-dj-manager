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
 * Recreate step cache on package or addon save.
 *
 * @since   1.5
 * @return  void
 */
function mdjm_event_builder_refresh_step_cache()  {
    delete_transient( 'mdjm_event_builder_steps' );
} // mdjm_event_builder_refresh_step_cache
add_action( 'mdjm_save_package', 'mdjm_event_builder_refresh_step_cache' );
add_action( 'mdjm_delete_package', 'mdjm_event_builder_refresh_step_cache' );
add_action( 'mdjm_save_addon', 'mdjm_event_builder_refresh_step_cache' );
add_action( 'mdjm_delete_addon', 'mdjm_event_builder_refresh_step_cache' );
