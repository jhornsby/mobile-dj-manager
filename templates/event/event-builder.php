<?php
/**
 * This template is used to display the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder.php
 */

$singular_label          = mdjm_get_label_singular();
$packages_enabled        = mdjm_packages_enabled();
$step                    = mdjm_event_builder_current_step();

$client_details_active   = $step > 1 ? ' class="active"' : '';
$event_details_active    = $step > 2 ? ' class="active"' : '';
$package_details_active  = $step > 3 ? ' class="active"' : '';
$addons_details_active   = $step > 4 ? ' class="active"' : '';

// Adjust these labels as required

$event_date_progress     = sprintf( __( '%s Date', 'mobile-dj-manager' ), $singular_label );
$client_details_progress = __( 'About You', 'mobile-dj-manager' );
$event_details_progress  = sprintf( __( 'Your %s', 'mobile-dj-manager' ), $singular_label );
$event_package_progress  = __( 'Package', 'mobile-dj-manager' );
$event_addons_progress   = __( 'Extras', 'mobile-dj-manager' );

$event_date_heading      = __( 'Select a Date', 'mobile-dj-manager' );
$client_details_heading  = __( 'Tell us About You', 'mobile-dj-manager' );
$event_details_heading   = sprintf( __( 'Your %s Details', 'mobile-dj-manager' ), $singular_label );
$event_package_heading   = __( 'Select a Package', 'mobile-dj-manager' );
$event_addons_heading    = sprintf( __( '%s Addons', 'mobile-dj-manager' ), $singular_label );

$prev_button_label       = __( 'Previous', 'mobile-dj-manager' );
$next_button_label       = __( 'Next', 'mobile-dj-manager' );
$submit_button_label     = __( 'Submit', 'mobile-dj-manager' );

// End of labels

do_action( 'mdjm_pre_event_builder' ); ?>

<div id="mdjm-event-builder-wrap">

    <?php do_action( 'mdjm_print_notices' ); ?>

    <div id="mdjm-event-builder-form-wrap" class="mdjm_clearfix">

        <div class="mdjm-alert mdjm-alert-error mdjm-hidden"></div>

        <?php do_action( 'mdjm_event_builder_before_form' ); ?>

        <form id="mdjm-event-builder-form" class="mdjm_form" autocomplete="off">

            <ul id="progress-bar">
                <li class="active"><?php echo $event_date_progress; ?></li>
                <li<?php echo $client_details_active; ?>><?php echo $client_details_progress; ?></li>
                <li<?php echo $event_details_active; ?>><?php echo $event_details_progress; ?></li>

                <?php if ( $packages_enabled ) : ?>
                    <li<?php echo $package_details_active; ?>><?php echo $event_package_progress; ?></li>
                <?php endif; ?>

                <?php do_action( 'mdjm_event_builder_progress_bar' ); ?>
            </ul>

            <!-- Form templates -->
            <?php include( mdjm_get_template_part( 'event', "builder-step-$step", false ) ); ?>

            <!-- Form buttons -->
            <fieldset>
                <?php if ( $step > 1 ) : ?>
                    <input type="button" name="previous" class="previous action-button" value="<?php echo $prev_button_label; ?>" data-step="<?php echo $step - 1; ?>" />
                <?php endif; ?>

                <?php if ( ! mdjm_event_builder_is_last_step() ) : ?>
                    <input type="button" name="next" class="next action-button" value="<?php echo $next_button_label; ?>" data-step="<?php echo $step + 1; ?>" />
                <?php else : ?>
                    <input type="submit" name="submit" id="mdjm-event-builder-submit" class="submit action-button" value="<?php echo $submit_button_label; ?>" />
                <?php endif; ?>
            </fieldset>

            <?php do_action( 'mdjm_event_builder_after_fieldsets' ); ?>

        </form>

        <?php do_action( 'mdjm_event_builder_after_form' ); ?>

    </div>
</div>

<?php do_action( 'mdjm_post_event_builder' ); ?>