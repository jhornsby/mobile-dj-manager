<?php
/**
 * This template is used to display step 2 of the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder-step-2.php
 */
?>
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

</fieldset>
