<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$content = $this->load->view('welcome_message', array(), true);
		$this->load->view('layouts/standard_page', array('content' => $content));
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */