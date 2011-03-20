<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

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
		
	function upload()
	{
		
	}	
		
	function view()
	{
		$page_name = $this->uri->segment(3);
		$blob_name = $this->uri->segment(4);
		
		if( $page_name && $blob_name ) {
			$media = $this->wiki_model->get_media( $page_name, $blob_name );
			if( $media->num_rows() > 0 ) {
				$media = $media->row();
				
				header('Content-type: ' . $media->blob_type );
				echo $media->blob_data;
				exit();
			}
		}
		
	}
}