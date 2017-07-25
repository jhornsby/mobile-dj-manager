<?php
/**
 * This template is used to display step 1 of the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder-step-1.php
 */
$value = ! empty( $post_data['event_date'] ) ? mdjm_format_short_date( $post_data['event_date'] ) : '';
?>
<fieldset>
    <legend><?php echo $event_date_heading; ?></legend>

    <?php do_action( 'mdjm_event_builder_before_date_field' ); ?>

    <p class="mdjm-event-date">
        <label for="event-date-display"><?php _e( 'Date', 'mobile-dj-manager' ); ?>
            <span class="mdjm-required-indicator">*</span>
        </label>
        <span class="mdjm-description"><?php _e( 'Select your event date', 'mobile-dj-manager' ); ?></span>
        <input type="date" name="event_date_display" id="event-date-display" class="mdjm-input mdjm-date" value="<?php echo $value; ?>" />
        <input type="hidden" name="event_date" id="event-date" value="<?php echo $post_data['event_date']; ?>" />
    </p>

    <?php do_action( 'mdjm_event_builder_after_date_field' ); ?>
</fieldset>
