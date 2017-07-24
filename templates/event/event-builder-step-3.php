<?php
/**
 * This template is used to display step 3 of the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder-step-3.php
 */
?>
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
</fieldset>
