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
        add_action( 'mdjm_export_pdf',                      array( $this, 'export_pdf' ) );
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

        $this->fPDF->SetFont('Arial');

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
			'subject' => ''
		);

		$meta = wp_parse_args( $meta, $defaults );

        $this->fPDF->SetCreator( MDJM_NAME . ', https://mdjm.co.uk' );

		if ( ! empty( $meta->title ) )	{
			$this->fPDF->SetTitle( $meta['title'] );
		}

		if ( ! empty( $meta->author ) )	{
			$this->fPDF->SetAuthor( $meta['author'] );
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
 -- CONTENT
------------------------------*/
    /**
     * Output the content to PDF and deliver as required
     * All vars should be sent via the URL query string
     * 
     * @since   1.5
     * @param   mixed   $data       The content to be converted. Post ID or string
     * @param   str     $output     'F'->File, 'I'->Browser, 'D'->Download, S->String
     * @param   str     $file       The full path to the file or the file name if $output = 'D'
     * @param   int|obj $event      An event ID or an MDJM_Event object
     * @return  mixed
     */
    public function write_content( $data, $output = 'F', $file = '', $event = false )    {

        if ( is_numeric( $data ) )   {
            $post    = get_post( $data );
            $content = $post->post_content;
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
        }

        if ( is_numeric( $event ) )	{
            $event     = new MDJM_Event( $event );
            $event_id  = $event->ID;
            $client_id = $event->client;
        } else	{
            $event_id  = $event;
            $client_id = '';
        }

        $event_id  = apply_filters( 'mdjm_pdf_event_id', $event_id, $data, $content );
        $client_id = apply_filters( 'mdjm_pdf_client_id', $client_id, $data, $content );
        $content   = mdjm_do_content_tags( $content, $event_id, $client_id );
        $content   = apply_filters( 'mdjm_pdf_content', $content, $event_id );

        if ( empty( $file ) )   {
            switch( $output )	{
                case 'F':
                default:
                    add_filter( 'upload_dir', array( $this, 'set_upload_dir' ) );
                    $upload_dir = wp_upload_dir();
                    $file = $upload_dir['path'] . '/' . str_replace( ' ', '_', mdjm_get_option( 'company_name' ) ) . '.pdf';
                    break;
                case 'I' :
                    $file = mdjm_get_option( 'company_name' ) . '.pdf';
                    break;
                case 'D' :
                    $file = mdjm_get_option( 'company_name' ) . '.pdf';
                    break;
                case 'S' :
                    $file = '';
                    break;	
            }
        }

        $file = apply_filters( 'mdjm_pdf_output_file', $file, $event_id, $content, $data );

        $this->init();
        $this->fPDF->AddPage();
        $this->fPDF->WriteHTML( $content );

    } // write_content

/*------------------------------
 -- FRONT END EXPORTS
------------------------------*/
    /**
	 * Export the front end content to PDF.
	 *
	 * @since	1.5
     * @param   arr     $data   Array of action data
	 * @return	void
	 */
    public function export_data( $data )    {
        if ( ! isset( $data['mdjm_nonce'] ) || ! wp_verify_nonce( $data['mdjm_nonce'], 'pdf-export' ) )    {
            return;
        }

        $action  = 'mdjm_' . sanitize_text_field( $data['output'] ) . '_pdf';
        $outputs = array(
            'download' => 'D',
            'print'    => 'I',
            'email'    => 'F'
        );

        $data['output'] = array_key_exists( $data['output'] ) ? $outputs[ $data['output'] ] : '';

        unset( $data['mdjm_nonce'], $data['mdjm_action'] );

        do_action( $action, $data );
    } // export_data

    /**
	 * Export and download.
	 *
	 * @since	1.5
     * @param   arr     $data   Array data
	 * @return	void
	 */
    public function export_download( $data )    {
        $content  = $data['content'];
        $output   = $data['output'];
        $file     = esc_html(  mdjm_get_option( 'company_name' ) ) . '.pdf';
        $event_id = $data['event_id'];

        $content  = apply_filters( 'mdjm_pdf_export_download', $content, $data, $file );

        $this->write_content( $content, $output, $file, $event_id );
        $this->fPDF->Output( $output, $file );
    } // export_download

    /**
	 * Export and print.
	 *
	 * @since	1.5
     * @param   arr     $data   Array data
	 * @return	void
	 */
    public function export_print( $data )    {
        $content  = $data['content'];
        $output   = $data['output'];
        $file     = esc_html(  mdjm_get_option( 'company_name' ) ) . '.pdf';
        $event_id = $data['event_id'];

        $content  = apply_filters( 'mdjm_pdf_export_print', $content, $data, $file );

        $this->write_content( $content, $output, $file, $event_id );
        $this->fPDF->Output( $output, $file );
    } // export_print

    /**
	 * Export and email.
	 *
	 * @since	1.5
     * @param   arr     $data   Array data
	 * @return	void
	 */
    public function export_email( $data )    {
        global $current_user;

        $content  = $data['content'];
        $output   = $data['output'];
        $file     = esc_html(  mdjm_get_option( 'company_name' ) ) . '.pdf';
        $event_id = $data['event_id'];

        $content  = apply_filters( 'mdjm_pdf_export_email', $content, $data, $file );

        $this->write_content( $content, $output, $file, $event_id );
        $this->fPDF->Output( $output, $file );

        if ( file_exists( $file ) ) {

            mdjm_send_email_content( array(
                'to_email'       => $current_user->user_email,
                'from_name'      => mdjm_get_option( 'company_name' ),
                'from_email'     => mdjm_get_option( 'system_email' ),
                'event_id'       => $event_id,
                'client_id'      => $current_user->ID,
                'subject'        => sprintf( __( 'Your file from %s', 'mobile-dj-manager' ), mdjm_get_option( 'company_name' ) ),
                'attachments'    => array( $file ),
                'message'        => __( 'Please find attached your requested file.', 'mobile-dj-manager' ),
                'track'          => true,
                'copy_to'        => 'disable',
                'source'         => __( 'PDF Export', 'mobile-dj-manager' )
            ) );

        } else  {
            
        }
    } // export_email

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

            $this->write_content( $data['mdjm_pdf_attach'], 'F', $file, $data['mdjm_email_event'] );
			$this->fPDF->Output( 'F', $file );

			if ( file_exists( $file ) )	{
				$files[] = $file;
			}

		}

		return $files;
	} // attach_pdf_to_comms

} // class
