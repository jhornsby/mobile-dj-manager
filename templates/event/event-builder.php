<?php
/**
 * This template is used to display the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.6
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-single.php
 */

$event_date_progress     = sprintf( __( '%s Date', 'mobile-dj-manager' ), mdjm_get_label_singular() );
$client_details_progress = __( 'About You', 'mobile-dj-manager' );
$event_details_progress  = sprintf( __( 'Your %s', 'mobile-dj-manager' ), mdjm_get_label_singular() );
$event_package_progress  = __( 'Extras', 'mobile-dj-manager' );

$event_date_heading      = __( 'Select a Date', 'mobile-dj-manager' );
$client_details_heading  = __( 'Tell us About You', 'mobile-dj-manager' );
$event_details_heading   = sprintf( __( 'Details of your %s', 'mobile-dj-manager' ), mdjm_get_label_singular() );
$event_package_heading   = __( 'Select a Package', 'mobile-dj-manager' );

$prev_button_label       = __( 'Previous', 'mobile-dj-manager' );
$next_button_label       = __( 'Next', 'mobile-dj-manager' );
$submit_button_label     = __( 'Submit', 'mobile-dj-manager' );

$packages_enabled        = mdjm_packages_enabled();

?>
<?php do_action( 'mdjm_pre_event_builder' ); ?>

<div id="mdjm-event-builder" class="mdjm_event_builder">

    <?php do_action( 'mdjm_print_notices' ); ?>

	<div class="mdjm-alert mdjm-alert-error mdjm-hidden"></div>

    <form id="mdjm-event-builder-form">

        <ul id="progress-bar">
            <li class="active"><?php echo $event_date_progress; ?></li>
            <li><?php echo $client_details_progress; ?></li>
            <li><?php echo $event_details_progress; ?></li>

            <?php if ( $packages_enabled ) : ?>
                <li><?php echo $event_package_progress; ?></li>
            <?php endif; ?>

        </ul>

        <!-- Event Date -->
        <fieldset>

            <h2 class="mdjm_eb_title"><?php echo $event_date_heading; ?></h2>
            <h3 class="mdjm_eb_subtitle"><?php _e( 'Step One', 'mobile-dj-manager' ); ?></h3>
            <input type="date" name="event_date_display" id="event-date-display" class="mdjm-input mdjm-date" placeholder="Date" />

            <input type="hidden" name="event_date" id="event-date" />
            <input type="button" name="next" class="next action-button date" value="<?php echo $next_button_label; ?>" />

        </fieldset>

        <!-- Client Details -->
        <fieldset>

            <h2 class="mdjm_eb_title"><?php echo $client_details_heading; ?></h2>
            <h3 class="mdjm_eb_subtitle"><?php _e( 'Step Two', 'mobile-dj-manager' ); ?></h3>
            <input type="text" name="client_firstname" id="client-first-name" placeholder="<?php _e( 'First Name', 'mobile-dj-manager' ); ?>" />
            <input type="text" name="client_lastname" id="client-last-name" class="mdjm-input" placeholder="<?php _e( 'Last Name', 'mobile-dj-manager' ); ?>" />
            <input type="email" name="client_email" id="client-email" class="mdjm-input" placeholder="<?php _e( 'Email Address', 'mobile-dj-manager' ); ?>" />
            <input type="tel" name="client_tel" id="client-tel" class="mdjm-input" placeholder="<?php _e( 'Telephone', 'mobile-dj-manager' ); ?>" />

            <input type="button" name="previous" class="previous action-button" value="<?php echo $prev_button_label; ?>" />
            <input type="button" name="next" class="next action-button" value="<?php echo $next_button_label; ?>" />

        </fieldset>

		<!-- Event Details -->
        <fieldset>

			<h2 class="mdjm_eb_title"><?php echo $event_details_heading; ?></h2>
            <h3 class="mdjm_eb_subtitle"><?php _e( 'Step Three', 'mobile-dj-manager' ); ?></h3>
            <input type="text" name="event_start_time" id="event-start-time" class="mdjm_timepicker mdjm-time" placeholder="<?php _e( 'Start Time', 'mobile-dj-manager' ); ?>" />
            <input type="text" name="event_finish_time" id="event-finish-time" class="mdjm_timepicker mdjm-time" placeholder="<?php _e( 'Finish Time', 'mobile-dj-manager' ); ?>" />
            <input type="date" name="event_finish_date_display" id="event-finish-date-display" class="mdjm-input mdjm-date" placeholder="Finish Date" />
            <input type="hidden" name="event_finish_date" id="event-finish-date" />

			<?php echo MDJM()->html->event_type_dropdown( array(
				'name'             => 'event_type',
				//'class'            => 'mdjm-input',
				'id'               => 'event-type'
			) ); ?>

			<input type="button" name="previous" class="previous action-button" value="<?php echo $prev_button_label; ?>" />
            <input type="button" name="next" class="next action-button" value="<?php echo $next_button_label; ?>" />

        </fieldset>

		<!-- Extras -->
        <?php if ( $packages_enabled ) : ?>
            <fieldset>

                <h2 class="mdjm_eb_title"><?php echo $event_details_heading; ?></h2>
                <h3 class="mdjm_eb_subtitle"><?php _e( 'Step Three', 'mobile-dj-manager' ); ?></h3>
                <input type="text" name="event_start_time" id="event-start-time" class="mdjm_timepicker mdjm-time" placeholder="<?php _e( 'Start Time', 'mobile-dj-manager' ); ?>" />
                <input type="text" name="event_finish_time" id="event-finish-time" class="mdjm_timepicker mdjm-time" placeholder="<?php _e( 'Finish Time', 'mobile-dj-manager' ); ?>" />
                <input type="date" name="event_finish_date_display" id="event-finish-date-display" class="mdjm-input mdjm-date" placeholder="Finish Date" />
                <input type="hidden" name="event_finish_date" id="event-finish-date" />

				<?php echo MDJM()->html->event_type_dropdown( array(
                    'name'             => 'event_type',
                    //'class'            => 'mdjm-input',
                    'id'               => 'event-type'
                ) ); ?>

                <input type="button" name="previous" class="previous action-button" value="<?php echo $prev_button_label; ?>" />
                <input type="submit" name="submit" class="submit action-button" value="<?php echo $submit_button_label; ?>" />

            </fieldset>
        <?php endif; ?>

    </form>

</div>

<?php do_action( 'mdjm_post_event_builder' ); ?>
