<?php
/**
 * Contains all event builder related functions
 *
 * @package		MDJM
 * @subpackage	Events
 * @since		1.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Default nuimber of steps in form.
 *
 * @since   1.5
 * @return  int     The number of steps
 */
function mdjm_event_builder_default_steps()  {
    $steps = 3;

    return apply_filters( 'mdjm_event_builder_default_steps', $steps );
} // mdjm_event_builder_default_steps

/**
 * Current steps in form.
 *
 * @since   1.5
 * @return  int     The current step
 */
function mdjm_event_builder_current_step()  {
    $step = isset( $_GET['mdjm_eb_step'] ) ? $_GET['mdjm_eb_step'] : 1;

    return (int)$step;
} // mdjm_event_builder_current_step

/**
 * Total number of steps in form.
 *
 * @since   1.5
 * @return  int     The number of steps
 */
function mdjm_event_builder_total_steps()   {
    $steps = get_transient( 'mdjm_event_builder_steps' );

    if ( false === $steps ) {

        if ( mdjm_packages_enabled() )    {
            $steps = mdjm_event_builder_default_steps();

            if ( count( mdjm_get_packages() ) > 0 ) {
                $steps++;
            }

            set_transient( 'mdjm_event_builder_steps', $steps, DAY_IN_SECONDS );
        }
    }

    return apply_filters( 'mdjm_event_builder_steps', $steps );
} // mdjm_event_builder_total_steps

/**
 * Whether or not the specified step is the final step.
 *
 * @since   1.5
 * @param   int     $step   The current step. Uses GET['mdjm_eb_step'] if not specified
 * @return  bool    True if last step, otherwise false
 */
function mdjm_event_builder_is_last_step( $step = null )  {
    if ( ! isset( $step ) ) {
        $step = mdjm_event_builder_current_step();
    }

    if ( $step == mdjm_event_builder_total_steps() )    {
        return true;
    }

    return false;
} // mdjm_event_builder_is_last_step
