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

$singular_label          = mdjm_get_label_singular();

$event_date_progress     = sprintf( __( '%s Date', 'mobile-dj-manager' ), $singular_label );
$client_details_progress = __( 'About You', 'mobile-dj-manager' );
$event_details_progress  = sprintf( __( 'Your %s', 'mobile-dj-manager' ), $singular_label );
$event_package_progress  = __( 'Extras', 'mobile-dj-manager' );

$event_date_heading      = __( 'Select a Date', 'mobile-dj-manager' );
$client_details_heading  = __( 'Tell us About You', 'mobile-dj-manager' );
$event_details_heading   = sprintf( __( 'Your %s Details', 'mobile-dj-manager' ), $singular_label );
$event_package_heading   = __( 'Select a Package', 'mobile-dj-manager' );

$prev_button_label       = __( 'Previous', 'mobile-dj-manager' );
$next_button_label       = __( 'Next', 'mobile-dj-manager' );
$submit_button_label     = __( 'Submit', 'mobile-dj-manager' );

$packages_enabled        = mdjm_packages_enabled();

do_action( 'mdjm_pre_event_builder' ); ?>

<div id="mdjm-event-builder-wrap">

    <?php do_action( 'mdjm_print_notices' ); ?>

    <div id="mdjm-event-builder-form-wrap" class="mdjm_clearfix">

        <div class="mdjm-alert mdjm-alert-error mdjm-hidden"></div>

        <?php do_action( 'mdjm_event_builder_before_form' ); ?>

        <form id="mdjm-event-builder-form" class="mdjm_form" autocomplete="off">

            <ul id="progress-bar">
                <li class="active"><?php echo $event_date_progress; ?></li>
                <li><?php echo $client_details_progress; ?></li>
                <li><?php echo $event_details_progress; ?></li>

                <?php if ( $packages_enabled ) : ?>
                    <li><?php echo $event_package_progress; ?></li>
                <?php endif; ?>

                <?php do_action( 'mdjm_event_builder_progress_bar' ); ?>
            </ul>

            <!-- Event Date -->
            <fieldset>
                <legend><?php echo $event_date_heading; ?></legend>

                <?php do_action( 'mdjm_event_builder_before_date_field' ); ?>

                <p class="mdjm-event-date">
                    <label for="event-date-display"><?php _e( 'Date', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php _e( 'Select your event date', 'mobile-dj-manager' ); ?></span>
                    <input type="date" name="event_date_display" id="event-date-display" class="mdjm-input mdjm-date" placeholder="Date" />
                    <input type="hidden" name="event_date" id="event-date" />
                </p>

                <button type="button" name="next" class="next action-button date"><?php echo $next_button_label; ?></button>

                <?php do_action( 'mdjm_event_builder_after_date_field' ); ?>
            </fieldset>

            <!-- Client Details -->
            <fieldset>
                <legend><?php echo $client_details_heading; ?></legend>

                <?php do_action( 'mdjm_event_builder_before_client_fields' ); ?>

                <p class="mdjm-client-firstname">
                    <label for="client-first-name"><?php _e( 'First Name', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php _e( 'So we know what to call you', 'mobile-dj-manager' ); ?></span>
                    <input type="text" name="client_firstname" id="client-first-name" />
                </p>

                <p class="mdjm-client-lastname">
                    <label for="client-last-name"><?php _e( 'Last Name', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php _e( 'For our records', 'mobile-dj-manager' ); ?></span>
                    <input type="text" name="client_lastname" id="client-last-name" />
                </p>

                <p class="mdjm-client-email">
                    <label for="client-email"><?php _e( 'Email Address', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php _e( 'So we can communicate with you', 'mobile-dj-manager' ); ?></span>
                    <input type="text" name="client_email" id="client-email" />
                </p>

                <p class="mdjm-client-tel">
                    <label for="client-tel"><?php _e( 'Telephone Number', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php printf( __( 'In case we need to discuss your %s details', 'mobile-dj-manager' ), strtolower( $singular_label ) ); ?></span>
                    <input type="tel" name="client_tel" id="client-tel" />
                </p>

                <?php do_action( 'mdjm_event_builder_after_client_fields' ); ?>

                <button type="button" name="previous" class="previous action-button"><?php echo $prev_button_label; ?></button>
                <button type="button" name="next" class="next action-button"><?php echo $next_button_label; ?></button>
            </fieldset>

            <!-- Event Details -->
            <fieldset>
                <legend><?php echo $event_details_heading; ?></legend>

                <?php do_action( 'mdjm_event_builder_before_event_fields' ); ?>

                <p class="mdjm-event-start">
                    <label for="client-tel"><?php _e( 'Start Time', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php printf( __( 'What time are you planning to start your %s?', 'mobile-dj-manager' ), strtolower( $singular_label ) ); ?></span>
                    <input type="text" name="event_start_time" id="event-start-time" class="mdjm_timepicker mdjm-time" />
                </p>

                <p class="mdjm-event-finish">
                    <label for="event-finish-time"><?php _e( 'Finish Time', 'mobile-dj-manager' ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php printf( __( 'What time will your %s finish?', 'mobile-dj-manager' ), strtolower( $singular_label ) ); ?></span>
                    <input type="text" name="event_finish_time" id="event-finish-time" class="mdjm_timepicker mdjm-time" />
                </p>

                <p class="mdjm-event-finish-date">
                    <label for="event-finish-date-display"><?php _e( 'Finish Date', 'mobile-dj-manager' ); ?></label>
                    <span class="mdjm-description"><?php printf( __( 'If your %s is across multiple days, enter the end date here', 'mobile-dj-manager' ), strtolower( $singular_label ) ); ?></span>
                    <input type="date" name="event_finish_date_display" id="event-finish-date-display" class="mdjm-input mdjm-date" />
                    <input type="hidden" name="event_finish_date" id="event-finish-date" />
                </p>

                <p class="mdjm-event-type">
                    <label for="event-type"><?php printf( __( '%s Type', 'mobile-dj-manager' ), $singular_label ); ?>
                        <span class="mdjm-required-indicator">*</span>
                    </label>
                    <span class="mdjm-description"><?php printf( __( 'What type of %s are you planning?', 'mobile-dj-manager' ), strtolower( $singular_label ) ); ?></span>
                    <?php echo MDJM()->html->event_type_dropdown( array(
                        'name' => 'event_type',
                        'id'   => 'event-type'
                    ) ); ?>
                </p>

                <?php do_action( 'mdjm_event_builder_after_event_fields' ); ?>

                <button type="button" name="previous" class="previous action-button"><?php echo $prev_button_label; ?></button>
                <button type="button" name="next" class="next action-button"><?php echo $next_button_label; ?></button>
            </fieldset>

            <!-- Extras -->
            <?php if ( $packages_enabled ) : ?>
                <fieldset>

                    <h2 class="mdjm_eb_title"><?php echo $event_details_heading; ?></h2>
                    <h3 class="mdjm_eb_subtitle"><?php _e( 'Step Three', 'mobile-dj-manager' ); ?></h3>

                    <?php do_action( 'mdjm_event_builder_before_package_field' ); ?>

                    <input type="text" name="event_start_time" id="event-start-time" class="mdjm_timepicker mdjm-time" placeholder="<?php _e( 'Start Time', 'mobile-dj-manager' ); ?>" />
                    <input type="text" name="event_finish_time" id="event-finish-time" class="mdjm_timepicker mdjm-time" placeholder="<?php _e( 'Finish Time', 'mobile-dj-manager' ); ?>" />
                    <input type="date" name="event_finish_date_display" id="event-finish-date-display" class="mdjm-input mdjm-date" placeholder="Finish Date" />
                    <input type="hidden" name="event_finish_date" id="event-finish-date" />

                    <?php echo MDJM()->html->event_type_dropdown( array(
                        'name'             => 'event_type',
                        //'class'            => 'mdjm-input',
                        'id'               => 'event-type'
                    ) ); ?>

                    <?php do_action( 'mdjm_event_builder_after_package_field' ); ?>

                    <input type="button" name="previous" class="previous action-button" value="<?php echo $prev_button_label; ?>" />
                    <input type="submit" name="submit" id="mdjm-event-builder-submit" class="submit action-button" value="<?php echo $submit_button_label; ?>" />

                </fieldset>
            <?php endif; ?>

            <?php do_action( 'mdjm_event_builder_after_fieldsets' ); ?>

        </form>

        <?php do_action( 'mdjm_event_builder_after_form' ); ?>

    </div>
</div>

<?php do_action( 'mdjm_post_event_builder' ); ?>
