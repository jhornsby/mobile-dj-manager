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
if ( $packages_enabled ) :
    $addons = mdjm_get_addons();
    ?>
    <fieldset>
        <legend><?php echo $event_addons_heading; ?></legend>

        <?php do_action( 'mdjm_event_builder_before_addons_field' ); ?>

        <?php foreach( $addons as $addon ) : ?>

        <p>
            <label for="addon-<?php echo $addon->ID; ?>">
                <input type="checkbox" name="addon[]" id="addon-<?php echo $addon->ID; ?>" /> 
                <?php echo get_the_title( $addon->ID ); ?>
                <?php if ( $package_prices ) : ?>
                    <?php echo ' &ndash; ' . mdjm_currency_filter( mdjm_format_amount( mdjm_get_addon_price( $addon->ID ) ) ); ?>
                <?php endif; ?>
            </label>
            <span class="mdjm-description"><?php echo mdjm_get_addon_excerpt( $addon->ID, 0 ); ?></span>
        </p>

        <?php endforeach; ?>

    </fieldset>
<?php endif; ?>
