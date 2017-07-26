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
 * Retrieve the event builder page ID.
 *
 * @since   1.5
 * @return  int|false   Page ID or false if not set
 */
function mdjm_get_event_builder_page()  {
    return mdjm_get_page_id( 'event_builder' );
} // mdjm_get_event_builder_page

/**
 * Retrieve labels for event builder form.
 *
 * @since   1.5
 * @param   str         $button   The input to which to retrieve label for. (previous, next, submit etc)
 * @return  str         Button label string
 */
function mdjm_get_event_builder_label( $input = 'submit' )  {
    return mdjm_get_option( 'event_builder_label_' . $input, ucfirst( $input ) );
} // mdjm_get_event_builder_label

/**
 * Whether or not to use offer packages.
 *
 * @since   1.5
 * @return  bool
 */
function mdjm_event_builder_offer_packages()  {
    if ( ! mdjm_packages_enabled() ) {
        return false;
    }

    return mdjm_get_option( 'event_builder_packages' );
} // mdjm_event_builder_offer_packages

/**
 * Whether or not to use offer addons.
 *
 * @since   1.5
 * @return  bool
 */
function mdjm_event_builder_offer_addons()  {
    if ( ! mdjm_packages_enabled() ) {
        return false;
    }

    return mdjm_get_option( 'event_builder_addons' );
} // mdjm_event_builder_offer_addons

/**
 * Whether or not to display package/addon prices.
 *
 * @since   1.5
 * @return  bool
 */
function mdjm_event_builder_display_package_prices()    {
    return mdjm_get_option( 'event_builder_display_prices' );
} // mdjm_event_builder_display_package_prices

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
        $steps = mdjm_event_builder_default_steps();

        if ( mdjm_event_builder_offer_packages() )    {
            if ( count( mdjm_get_packages() ) > 0 ) {
                $steps++;
            }
        }

        if ( mdjm_event_builder_offer_addons() )    {
            if ( count( mdjm_get_addons() ) > 0 ) {
                $steps++;
            }
        }

        set_transient( 'mdjm_event_builder_steps', $steps, DAY_IN_SECONDS );
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

/**
 * Retrieve posted form data.
 *
 * @since   1.5
 * @param   str         $key   The transient key within which data is stored. Check $_POST if not defined.
 * @return  arr|false   Array of posted data or false if no data
 */
function mdjm_get_event_builder_post_data( $key = '' )  {
    if ( empty( $key ) )    {
        if ( isset( $_POST['mdjm_eb_key'] ) )   {
            $key = $_POST['mdjm_eb_key'];
        }
    }

    $post_data = get_transient( $key );

    if ( false === $post_data ) {
        foreach( mdjm_event_builder_fields() as $field ) {
            $post_data[ $field ] = '';
        }
    }

    return apply_filters( 'mdjm_event_builder_post_data', $post_data, $key );
} // mdjm_get_event_builder_post_data

/**
 * Fields used within the event builder.
 *
 * @since   1.5
 * @return  arr   Array of fields
 */
function mdjm_event_builder_fields()    {
    // Array key should be equal to the step within the form
    $fields = array(
        'event_date',
        'client_firstname',
        'client_lastname',
        'client_email',
        'client_tel',
        'event_start_time',
        'event_finish_time',
        'event_finish_date',
        'event_type'
    );

    if ( mdjm_event_builder_offer_packages() )  {
        $fields[] = 'package';
    }

    if ( mdjm_event_builder_offer_addons() )    {
        $fields[] = 'addon';
    }

    return apply_filters( 'mdjm_event_builder_fields', $fields );
} // mdjm_event_builder_fields

/**
 * Required fields.
 *
 * @since   1.5
 * @param	int		$step	Step
 * @return  arr		Array of required fields
 */
function mdjm_event_builder_required_fields( $step = null ) {
	if ( ! isset( $step ) )	{
		if ( isset( $_POST['step'] ) )	{
			$step = $_POST['step'];
		} else	{
			mdjm_event_builder_current_step();
		}
	}

	switch ( $step )	{
		case '1':
			$fields = array( 'event_date' );
			break;
		
		case '2':
			$fields = array( 'client_firstname', 'client_lastname', 'client_email' );
			break;

		case '3':
			$fields = array( 'event_start_time', 'event_finish_time' );
			break;

		default:
			$fields = array();
	}

    return apply_filters( 'mdjm_event_builder_required_fields', $fields );
} // mdjm_event_builder_required_fields

/**
 * Form data to ignore.
 *
 * @since   1.5
 * @return  arr   Array of fields to ignore
 */
function mdjm_event_builder_ignore_fields() {
    $fields = array( 'mdjm_eb_key', 'event_date_display', 'step', 'action', 'mdjm_honeypot' );

    return apply_filters( 'mdjm_event_builder_ignore_fields', $fields );
} // mdjm_event_builder_ignore_fields
