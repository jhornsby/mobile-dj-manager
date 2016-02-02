<?php
/**
 * Check the current page & post type and fire the MDJM MCE Shortcode button if required
 * 
 * @called	'admin_head'
 *
 * @param
 *
 * @return
 */
function mdjm_display_shortcode_button()	{    
    // Define the post types & screens within which the MCE button should be displayed
    $post_types = array( 'email_template', 'contract', 'page' );
    $screens = array( 
        'mdjm-events_page_mdjm-comms',
        'mdjm-events_page_mdjm-settings' );
    
    /* -- Add the MDJM TinyMCE buttons -- */
    $screen = get_current_screen();
    if( in_array( get_post_type(), $post_types ) || in_array( $screen->id, $screens ) )	{
		// Check if WYSIWYG is enabled & add the filters
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', 'mdjm_register_mce_plugin' );
			add_filter( 'mce_buttons', 'mdjm_register_mce_buttons' );
		}
	}    
} // mdjm_display_shortcode_button
add_action( 'admin_head', 'mdjm_display_shortcode_button' );

/**
 * Register the script that inserts ths MDJM Shortcodes into the content
 * when the MDJM Shortcode button is used
 *
 * @called	mce_external_plugins
 *
 * @param	arr		$plugin_array		Array of registered MCE plugins
 *
 *
 */
function mdjm_register_mce_plugin( $plugin_array ) {
	$plugin_array['mdjm_shortcodes_btn'] = MDJM_PLUGIN_URL . '/assets/js/mdjm-tinymce-shortcodes.js';
	return $plugin_array;
} // mdjm_register_mce_plugin

/*
 * Register the MDJM Shortcode button within the TinyMCE interface
 * 
 * @called	mce_buttons
 *
 * @params	arr		$buttons	Array of registered MCE buttons.
 *
 * @return	arr		$buttons	Filtered array of registered MCE buttons.
 */
function mdjm_register_mce_buttons( $buttons ) {
	array_push( $buttons, 'mdjm_shortcodes_btn' );
	return $buttons;
} // mdjm_register_mce_buttons

/*
 * Remove the Add New button from the post display for specified post types
 * 
 * @params
 * 
 * @return
 */
function mdjm_remove_add_new() {
	if( !isset( $_GET['post_type'] ) )
		return;
	
	/**
	 * Remove the Add New button from the post lists display for all posts within the 
	 * $no_add_new array
	 */
	$no_add_new = array( MDJM_COMM_POSTS, MDJM_QUOTE_POSTS );
	
	if( in_array( $_GET['post_type'], $no_add_new ) )	{
		?>
		<style type="text/css">
			.page-title-action	{
				display: none;	
			}
		</style>
		<?php
	}
} // mdjm_remove_add_new
add_action( 'admin_head', 'mdjm_remove_add_new' );

/**
 * Removes filter from the post lists display for specified post types
 *
 * @params
 *
 * @return
 */
function mdjm_remove_post_filters()	{
	if( !isset( $_GET['post_type'] ) )
		return;
	/**
	 * Remove the date filters from the venue post lists display for all posts within the 
	 * $no_date_filter array
	 */
	$no_date_filter = array( MDJM_EVENT_POSTS );
	
	if( in_array( $_GET['post_type'], $no_date_filter ) )
		add_filter( 'months_dropdown_results', '__return_empty_array' );
	
	/**
	 * Remove all filters from the venue post lists display for all posts within the 
	 * $no_filter array
	 */
	$no_filter = array( MDJM_VENUE_POSTS );
	
	if( in_array( $_GET['post_type'], $no_filter ) )	{
		?>
		<style type="text/css">
			#posts-filter .tablenav select[name=m],
			#posts-filter .tablenav select[name=cat],
			#posts-filter .tablenav #post-query-submit{
				display:none;
			}
		</style>
		<?php	
	}
} // mdjm_remove_post_filters
add_action( 'admin_head', 'mdjm_remove_post_filters' );

/**
 * Set the title of a custom post upon new creation & make it readonly
 * 
 * @params	obj		$post		The post object
 *
 * @return	void
 */
function mdjm_set_post_title( $post ) {
	// Only apply to events and transactions
	if( get_post_type() != MDJM_EVENT_POSTS && get_post_type() != MDJM_TRANS_POSTS )
		return;
	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#title").val("<?php echo MDJM_EVENT_PREFIX . $post->ID; ?>");
			$("#title").prop("readonly", true);
		});
	</script>
	<?php
} // mdjm_set_post_title
add_action( 'edit_form_after_title', 'mdjm_set_post_title' );

/**
 * Set the post title placeholder for custom post types
 * 
 *
 * @param    str	$title		The post title
 *
 * @return   str	$title		The filtered post title
 */
function mdjm_post_title_placeholder( $title )	{
	global $post;
	
	if( !isset( $post ) )
		return;
	
	switch( get_post_type() )	{
		case MDJM_CONTRACT_POSTS:
			return __( 'Enter Contract name here...', 'mobile-dj-manager' );
		break;
		case MDJM_EMAIL_POSTS:
			return __( 'Enter Template name here. Used as email subject, shortcodes allowed', 'mobile-dj-manager' );
		break;
		case MDJM_VENUE_POSTS:
			return __( 'Enter Venue name here...', 'mobile-dj-manager' );
		break;
		default:
			return $title;
		break;
	}	
} // mdjm_post_title_placeholder
add_filter( 'enter_title_here', 'mdjm_post_title_placeholder' );

/**
 * Sets the name for the publish button for each custom post type
 * 
 * @called 	gettext
 *
 * @params	str		$translation	The current button text translation
 * 			str		$text			The text for the button
 * 
 * @return	str		$translation	The filtererd button text translation
 */
function mdjm_rename_publish_button( $translation, $text )	{
	global $post;
	
	if( !isset( $post ) )
		return $translation;
	
	switch( get_post_type() )	{
		case MDJM_CONTRACT_POSTS:
			if( $text == 'Publish' )
				return __( 'Save Contract', 'mobile-dj-manager' );
			elseif( $text == 'Update' )
				return __( 'Update Contract', 'mobile-dj-manager' );
			else
				return $translation;
		break;
		
		case MDJM_EMAIL_POSTS:
			if( $text == 'Publish' )
				return __( 'Save Template', 'mobile-dj-manager' );
			elseif( $text == 'Update' )
				return __( 'Update Template', 'mobile-dj-manager' );
			else
				return $translation;
		break;
		
		case MDJM_EVENT_POSTS:
			$event_stati = mdjm_all_event_status();
			
			if( $text == 'Publish' && isset( $event_stati[$post->post_status] ) )
				return __( 'Update Event', 'mobile-dj-manager' );
			elseif( $text == 'Publish' )
				return __( 'Create Event', 'mobile-dj-manager' );
			elseif( $text == 'Update' )
				return __( 'Update Event', 'mobile-dj-manager' );
			else
				return $translation;
		break;
		
		case MDJM_TRANS_POSTS:
			if( $text == 'Publish' )
				return __( 'Save Transaction', 'mobile-dj-manager' );
			elseif( $text == 'Update' )
				return __( 'Update Transaction', 'mobile-dj-manager' );
			else
				return $translation;
		break;
		
		case MDJM_VENUE_POSTS:
			if( $text == 'Publish' )
				return __( 'Save Venue', 'mobile-dj-manager' );
			elseif( $text == 'Update' )
				return __( 'Update Venue', 'mobile-dj-manager' );
			else
				return $translation;
		break;
		
		default:
			return $translation;
		break;
	}
	
	return $translation;
} // mdjm_rename_publish_button
add_filter( 'gettext', 'mdjm_rename_publish_button', 10, 2 );

/**
 * Highlighted table rows for unattended events within event post listings
 *
 *
 *
 *
 */
function mdjm_highlight_unattended_event_rows()	{
	global $post;
			
	if( !isset( $post ) || $post->post_type != MDJM_EVENT_POSTS )
		return;
	
	?>
	<style>
	/* Color by post Status */
	.status-mdjm-unattended	{
		background: #FFEBE8 !important;
	}
	</style>
	<?php
} // mdjm_highlight_unattended_event_rows
add_action( 'admin_footer', 'mdjm_highlight_unattended_event_rows' );

/**
 * Append MDJM to the Thank you for creating with WordPress footer text
 *
 * @since	1.3
 * @param	str		$text	The footer text
 * @return	str		Filtered footer text string
 */
function mdjm_admin_footer_text( $text )	{
	$text .= ' ';
	$text .= '<em>' . sprintf( __( 'Powered by <a class="mdjm-admin-footer" href="%s" target="_blank">MDJM Event Management, version %s</a>.', 'mobile-dj-manager' ), 
		mdjm_get_admin_page( 'mydjplanner', 'str' ),
		MDJM_VERSION_NUM ) . '</em>';
		
	return $text;
} // mdjm_admin_footer_text
add_filter( 'admin_footer_text', 'mdjm_admin_footer_text' );
?>