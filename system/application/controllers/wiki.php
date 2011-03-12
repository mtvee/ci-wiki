<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends Controller
{

	function __construct()
	{
		parent::__construct();
		
    $this->load->model('wiki_model');
    $this->load->helper('textile');
		$this->load->library('creole');
	}

	/**
	 * this is the only publicly available method. The 2nd segment of the
	 * url is the page name rather then the method. If you move the controller
	 * deeper or shallower you have to adjust the offsets here. Operations are
	 * the final element of the URL, for edit, history, etc and those methods
	 * are protected.
	 */
	function _remap()
	{
    $page_name = $this->uri->segment(2);
    if( !$page_name ) {
      $page_name = 'Index';
    }

    // figure out if there is an operation call
		// we have two types of operations, site level and page level
		// site level calls are made with page name = 'ciwiki'
		if( $page_name == 'ciwiki' ) {
	    if( $this->uri->segment(3,'') == 'changes') {
				$this->changes();
				return;
			}
	    if( $this->uri->segment(3,'') == 'index') {
				$this->site_index();
				return;
			}
		}
		
		// page level calls
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
		
		// handle data submission
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

		// find the page
    $page = $this->wiki_model->get_page( $page_name );
    
		// page is empty, so edit it automatically
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
						$c = new creole(array('link_format' => site_url() . '/wiki/%s'));
						$page->body = $c->parse($page->body);
						break;
				} 
      }
    }
		
		// data for the view
		$view_data = array(
      'page' => $page,
      'errors' => ''
			);
		// content is conditional on edit operation
    if( $editing ) {
			$content = $this->load->view('wiki/page_edit', $view_data, true );
    } else {
			$content = $this->load->view('wiki/page_view', $view_data, true );
    }

		// data for the layout view
		$pg_data = array(
			'content' => $content,
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - ' . $page->title
		);
		// render
		$this->load->view('layouts/standard_page', $pg_data );
	}

	// generate the history view for a given page
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
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - History:' . $page->title
		);
		
		$this->load->view('layouts/standard_page', $pg_data );
		
  }

	// generate the diff view for a given page revision
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
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - Revision:' . $page_name
		);

		$this->load->view('layouts/standard_page', $pg_data );
			
	}

	// generate the diff view for a given page revision
	protected function changes()
	{
    $view_data = array(
			'changes' => $this->wiki_model->recent_changes(),
      'errors' => ''
      );

		$content = $this->load->view('wiki/ciwiki_changes', $view_data, true );	

		$pg_data = array(
			'content' => $content,
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - Recent Changes'
		);

		$this->load->view('layouts/standard_page', $pg_data );			
	}


	// generate the diff view for a given page revision
	protected function site_index()
	{
    $view_data = array(
			'pages' => $this->wiki_model->site_index(),
      'errors' => ''
      );

		$content = $this->load->view('wiki/ciwiki_site_index', $view_data, true );	

		$pg_data = array(
			'content' => $content,
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - Recent Changes'
		);

		$this->load->view('layouts/standard_page', $pg_data );			
	}



	protected function mk_nav()
	{
		$nav = '<h3>Toolbox</h3>';
		$nav .= '<ul class="vertical-nav">';
		$nav .= '<li><a href="' . site_url() .'/wiki">wiki home</a></li>';
		//$nav .= '<li><a href="' . site_url() .'">what links here</a></li>';
		$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/changes">recent changes</a></li>';
		$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/index">site index</a></li>';
		$nav .= '</ul>';
		return $nav;
	}

}
