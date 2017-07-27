<?php
/**
 * This template is used to display the package step of the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder-step-package.php
 */
if ( $packages_enabled ) :
    $packages = mdjm_get_packages();
	$checked  = empty( $post_data['package'] ) ? ' checked="checked"' : '';
    ?>
    <fieldset>
        <legend><?php echo $event_package_heading; ?></legend>

        <?php do_action( 'mdjm_event_builder_before_packages_field' ); ?>

        <p>
            <label for="package-0">
                <input type="radio" name="package" id="package-0" value="0"<?php echo $checked; ?> /> 
                <?php echo $no_package_label; ?>
            </label>
        </p>

        <?php foreach( $packages as $package ) : ?>

        <p>
            <label for="package-<?php echo $package->ID; ?>">
                <input type="radio" name="package" id="package-<?php echo $package->ID; ?>" value="<?php echo $package->ID; ?>"<?php checked( $post_data['package'], $package->ID ); ?> /> 
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
