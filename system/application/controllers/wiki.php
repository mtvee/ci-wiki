<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Wiki extends Controller
{

	function __construct()
	{
		parent::__construct();
		
    $this->load->model('wiki_model');
    $this->load->helper('textile');
    $this->load->helper('url');
	}

	
	function _remap()
	{
    $page_name = $this->uri->segment(2);
    if( !$page_name ) {
      $page_name = 'Index';
    }
    
    $editing = false;
    if( $this->uri->segment(3,'') == 'edit' ) {
      $editing = true;
    }
    if( $this->uri->segment(3,'') == 'history' ) {
      $this->history( $page_name );
      return;
    }
    if( $this->uri->segment(3,'') == 'diff' ) {
      $this->diff( $page_name, $this->uri->segment(4) );
      return;
    }

    if( $this->input->post('save')) {
      $id = $this->input->post('id');
      $title = $this->input->post('title');
      $body = $this->input->post('bodytext');
      if( $id == -1 ) {
        $this->wiki_model->add_page( $title, $body, 'testuser' );
      } else {
        $this->wiki_model->update_page( $id, $title, $body, 'testuser' );
      }
      $editing = false;
    }

    $page = $this->wiki_model->get_page( $page_name );
    
    if( !$page ) {
      $page = new StdClass();
      $page->id = -1;
      $page->title = $page_name;
      $page->body = '';
      $editing = true;
    } else {
      if( !$editing ) {
        $page->body = textile_text($page->body);
      }
    }

		$pg_data = array(
      'page' => $page,
      'errors' => ''
			);

    if( $editing ) {
			$this->load->view('wiki/page_edit', $pg_data );
    } else {
			$this->load->view('wiki/page_view', $pg_data );
    }
	}

  function history( $page_name )
  {
    $page = $this->wiki_model->get_page( $page_name );
    if( $page ) {
      $revisions = $this->wiki_model->get_revisions( $page->id );
      $revisions = $revisions->result();
    } else {
      $revisions = array();
      $page = new StdClass();
      $page->title = $page_name;
    }

    $pg_data = array(
      'page' => $page,
      'revisions' => $revisions,
      'errors' => ''
      );

		$this->load->view('wiki/page_history', $pg_data );
  }

	function diff( $page_name, $id )
	{
    $pg_data = array(
			'diff' => $this->wiki_model->get_revision( $id )->row(),
      'errors' => ''
      );

		$this->load->view('wiki/page_diff', $pg_data );		
	}

}
