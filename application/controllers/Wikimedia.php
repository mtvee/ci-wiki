<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This controller serves up media from the database
 */
class WikiMedia extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->library('wiki_auth');		
    $this->load->model('wiki_model');		
	}
	
	function index()
	{
		// this does nothing by default
	}

	function download( $page_name = null, $blob_name = null )
	{	
		$data = $this->get_data( $page_name, $blob_name );
		if( $data ) {
			// Set headers
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=$blob_name");
	    header("Content-Type: " . $data['type'] );
	    header("Content-Transfer-Encoding: binary");
			echo $data['data'];
			exit();
		}
	}
				
	function view( $page_name = null, $blob_name = null )
	{	
		$data = $this->get_data( $page_name, $blob_name );
		if( $data ) {
			header('Content-type: ' . $data['type'] );
			echo $data['data'];
			exit();			
		}
	}

	/**
	 * Get the data from the db or null if not found.
	 *
	 * @return array('type' => mime-type, 'data' => blob )
	 */
	protected function get_data( $page_name, $blob_name )
	{
		if( $page_name && $blob_name ) {
			$media = $this->wiki_model->get_media( $page_name, $blob_name );
			if( $media->num_rows() > 0 ) {
				$media = $media->row();
				return array( 'type' => $media->blob_type, 'data' => $media->blob_data );
			}
		}
		return null;		
	}
	
}