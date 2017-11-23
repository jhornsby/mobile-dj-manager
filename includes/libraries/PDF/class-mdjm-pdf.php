<?php
/**
 * PDF Library
 *
 * @package     MDJM
 * @subpackage  Classes/PDF
 * @copyright   Copyright (c) 2016, Mike Howard
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class MDJM_PDF	{

	private $fPDF;
	private $page_size;
	private $page_orientation;
	private $meta_defined = false;

	/*
	 * Get things going
	 */
	public function __construct()	{
		add_action( 'mdjm_add_comms_fields_before_content', array( $this, 'comms_page_pdf_attachment_input' ) );
		add_filter( 'mdjm_send_comm_email_attachments',     array( $this, 'attach_pdf_to_comms'             ), 10, 2 );
	} // __construct

	/**
	 * Initialise PDF
	 *
	 * @since	1.5
	 */
	public function init()	{
		$this->page_size        = mdjm_get_option( 'pdf_page_size' );
		$this->page_orientation = mdjm_get_option( 'pdf_page_orientation' );

		require_once( MDJM_PLUGIN_DIR . '/includes/libraries/PDF/html2pdf.php' );
		$this->fPDF = new PDF_HTML( $this->page_orientation, 'mm', $this->page_size );

		if ( ! $this->meta_defined )	{
			$this->set_meta();
		}

	} // init

	/**
	 * Set PDF Metadata
	 *
	 * @since	1.5
	 * @param	arr		$meta	Array of PDF meta
	 */
	public function set_meta( $meta = array() )	{

		$company = mdjm_get_option( 'company_name' );

		$defaults = array(
			'title'   => '',
			'author'  => $company,
			'creator' => $company,
			'subject' => ''
		);

		$meta = wp_parse_args( $meta, $defaults );

		if ( ! empty( $meta->title ) )	{
			$this->fPDF->SetTitle( $meta['title'] );
		}

		if ( ! empty( $meta->author ) )	{
			$this->fPDF->SetAuthor( $meta['author'] );
		}

		if ( ! empty( $meta->creator ) )	{
			$this->fPDF->SetCreator( $meta['creator'] );
		}

		if ( ! empty( $meta->subject ) )	{
			$this->fPDF->SetSubject( $meta['subject'] );
		}

		$this->meta_defined = true;

	} // set_meta

	/**
	 * Set the upload DIR for PDF files.
	 *
	 * @since	1.5
	 * @param	str			$upload	The file name including path
	 * @return	str|bool	The file path or false if failed
	 */
	public function set_upload_dir( $upload )	{
		// Override the year / month being based on the post publication date, if year/month organization is enabled
		if ( get_option( 'uploads_use_yearmonth_folders' ) )	{
			$time             = current_time( 'mysql' );
			$y                = substr( $time, 0, 4 );
			$m                = substr( $time, 5, 2 );
			$upload['subdir'] = "/$y/$m";
		}

		$upload['subdir'] = '/mdjm_pdf' . $upload['subdir'];
		$upload['path']   = $upload['basedir'] . $upload['subdir'];
		$upload['url']    = $upload['baseurl'] . $upload['subdir'];

		return apply_filters( 'mdjm_set_pdf_upload_dir', $upload );
	} // set_upload_dir

	/**
	 * Retrieve the upload DIR for PDF files.
	 *
	 * @since	1.5
	 * @param	$key	The array key to return or false for the entire array.
	 * @return	str
	 */
	function get_upload_dir( $key = false )	{
		add_filter( 'upload_dir', 'set_upload_dir' );

		$upload_dir = wp_upload_dir();

		if ( $key && isset( $upload_dir[ $key ] ) )	{
			$dir = $upload_dir[ $key ];
		} else	{
			$dir = $upload_dir;
		}

		return $dir;
	} // get_upload_dir

/*------------------------------
 -- COMMUNICATIONS PAGE
------------------------------*/
	/**
	 * Adds the PDF input field to the communications screen.
	 *
	 * @since	1.5
	 * @return	void
	 */
	public function comms_page_pdf_attachment_input()	{
		$selected = empty( $_POST['mdjm_pdf_attach'] ) ? ' selected="selected"' : '';
		?>
        <tr>
            <th scope="row">
            	<label for="pdf_attach"><?php _e( 'Attach PDF Template', 'mobile-dj-manager' ); ?>:</label>
            </th>
            <td>
                <select name="mdjm_pdf_attach" id="mdjm_pdf_attach">
                    <option value="0"<?php echo $selected; ?>>
                        <?php _e( 'No Attachment', 'mobile-dj-manager' ); ?>
                    </option>
	                <?php echo mdjm_comms_template_options(); ?>
                </select>
                <p class="description">
                	<?php _e( 'Select a template to attach to your email as a PDF', 'mobile-dj-manager' ); ?>
                </p>
            </td>
        </tr>
        <?php
	} // comms_page_pdf_attachment_input

	/**
	 * Attach the selected template as a PDF file to a communication.
	 *
	 * @since	1.5
	 * @param	arr		$files	Array of file attachments
	 * @param	arr		$data	Array of post data
	 * @return	Array of file attachments
	 */
	public function attach_pdf_to_comms( $files, $data )	{

		if ( ! empty( $data['mdjm_pdf_attach'] ) && ! empty( $data['mdjm_email_event'] ) )	{
			$this->init();

			add_filter( 'upload_dir', array( $this, 'set_upload_dir' ) );

			$upload_dir = wp_upload_dir();
			$mdjm_event = new MDJM_Event( $data['mdjm_email_event'] );
			$template   = get_post( $data['mdjm_pdf_attach'] );
			$content    = $template->post_content;
			$content    = apply_filters( 'the_content', $content );
			$content    = str_replace( ']]>', ']]&gt;', $content );
			$content    = mdjm_do_content_tags( $content, $mdjm_event->ID, $mdjm_event->client );
			$file       = $upload_dir['path'] . '/' . str_replace( ' ', '_', mdjm_get_option( 'company_name' ) ) . '_' . mdjm_get_event_contract_id( $_POST['mdjm_email_event'] ) . '-' . date( 'Y-m-d H:i:s' ) . '.pdf';

			$this->fPDF->WriteHTML( $content );
			$this->fPDF->Output( 'F', $file );

			if ( file_exists( $file ) )	{
				error_log( '333' );
				$files[] = $file;
			}

		}

		return $files;
	} // attach_pdf_to_comms

} // class
