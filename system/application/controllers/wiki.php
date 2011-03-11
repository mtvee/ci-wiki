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
		$this->load->library('creole');
	}

	
	function _remap()
	{
    $page_name = $this->uri->segment(2);
    if( !$page_name ) {
      $page_name = 'Index';
    }
    
    $editing = false;
    if( $this->uri->segment(3,'') == 'edit') {
      $editing = true;
    }
    if( $this->input->post('cancel')) {
			$editing = false;
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
				/* TODO add username once auth is working */
        $this->wiki_model->add_page( $title, $body, 'guest' );
      } else {
        $this->wiki_model->update_page( $id, $title, $body, 'guest' );
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
				$parser = $this->config->item('wiki_parser');
				switch( $parser ) {
					case 'textile':
	        	$page->body = textile_text($page->body);
						break;
					default:
						$c = new creole();
						$page->body = $c->parse($page->body);
						break;
				} 
      }
    }

		$view_data = array(
      'page' => $page,
      'errors' => ''
			);

    if( $editing ) {
			$content = $this->load->view('wiki/page_edit', $view_data, true );
    } else {
			$content = $this->load->view('wiki/page_view', $view_data, true );
    }

		$pg_data = array(
			'content' => $content,
			'page_title' => 'CI-Wiki - ' . $page->title
		);

		$this->load->view('layouts/standard_page', $pg_data );
	}

  protected function history( $page_name )
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

    $view_data = array(
      'page' => $page,
      'revisions' => $revisions,
      'errors' => ''
      );

		$content = $this->load->view('wiki/page_history', $view_data, true );
		
		$pg_data = array(
			'content' => $content,
			'page_title' => 'CI-Wiki - History:' . $page->title
		);
		
		$this->load->view('layouts/standard_page', $pg_data );
		
  }

	protected function diff( $page_name, $id )
	{
    $view_data = array(
			'diff' => $this->wiki_model->get_revision( $id )->row(),
			'title' => $page_name,
      'errors' => ''
      );

		$content = $this->load->view('wiki/page_diff', $view_data, true );	

		$pg_data = array(
			'content' => $content,
			'page_title' => 'CI-Wiki - Revision:' . $page_name
		);

		$this->load->view('layouts/standard_page', $pg_data );
			
	}

}
