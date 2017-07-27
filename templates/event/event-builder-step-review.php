<?php
/**
 * This template is used to display the review step of the event builder form.
 *
 * @version 		1.0
 * @author			Mike Howard
 * @since			1.5
 * @shortcodes		Supported
 *
 * Do not customise this file!
 * If you wish to make changes, copy this file to your theme directory /theme/mdjm-templates/event/event-builder-step-review.php
 */
?>
<h2 class="review_header"><?php echo $event_review_heading; ?></h2>
<p class="review_intro"></p>
<fieldset>
    <legend><?php echo $event_review_heading; ?></legend>

    <?php do_action( 'mdjm_event_builder_before_review' ); ?>

    <p>
        Review your EVENT!!!
    </p>

</fieldset>
