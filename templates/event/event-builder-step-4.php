<?php
/**
 * This template is used to display step 5 of the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder-step-5.php
 */
if ( $packages_enabled ) :
    $packages = mdjm_get_packages();

    ?>
    <fieldset>
        <legend><?php echo $event_package_heading; ?></legend>

        <?php do_action( 'mdjm_event_builder_before_packages_field' ); ?>

        <?php foreach( $packages as $package ) : ?>

        <p>
            <label for="package-<?php echo $package->ID; ?>">
                <input type="checkbox" name="package[]" id="package-<?php echo $package->ID; ?>" /> 
                <?php echo get_the_title( $package->ID ); ?>
                <?php if ( $package_prices ) : ?>
                    <?php echo ' &ndash; ' . mdjm_currency_filter( mdjm_format_amount( mdjm_get_package_price( $package->ID ) ) ); ?>
                <?php endif; ?>
            </label>
            <span class="mdjm-description"><?php echo mdjm_get_package_excerpt( $package->ID, 0 ); ?></span>
        </p>

        <?php endforeach; ?>

    </fieldset>
<?php endif; ?>
