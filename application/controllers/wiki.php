<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		// lamguage stuffs
		$this->lang->load('general','english');
		$this->load->helper('language');
		
		$this->load->library('wiki_auth');		
    $this->load->model('wiki_model');
		$this->load->library('ciwiki_parser');
		$this->ciwiki_parser->link_format = site_url() . '/wiki/%s';
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
		// this is the page name offset
		$url_offs = 2;
		
    $page_name = urldecode($this->uri->segment( $url_offs ));
    if( !$page_name ) {
      $page_name = 'Index';
    }

    // figure out if there is an operation call
		// we have two types of operations, site level and page level
		// site level calls are made with page name = 'ciwiki'
		if( $page_name == 'ciwiki' ) {
	    if( $this->uri->segment( $url_offs + 1,'') == 'changes') {
				$this->changes();
				return;
			}
	    if( $this->uri->segment( $url_offs + 1,'') == 'index') {
				$this->site_index();
				return;
			}
	    if( $this->uri->segment( $url_offs + 1,'') == 'search') {
				$this->search();
				return;
			}
	    if( $this->uri->segment( $url_offs + 1,'') == 'login') {
				$this->login();
				return;
			}
	    if( $this->uri->segment( $url_offs + 1,'') == 'logout') {
				$this->wiki_auth->logout();
				redirect("/wiki");
			}
		}
		
		// page level calls
    $editing = false;
		$raw = false;
    if( $this->uri->segment( $url_offs + 1,'') == 'edit' && $this->wiki_auth->logged_in()) {
      $editing = true;
    }
    if( $this->input->post('cancel')) {
			$editing = false;
		}
		
    if( $this->uri->segment( $url_offs + 1,'') == 'history' ) {
      $this->history( $page_name );
      return;
    }
    if( $this->uri->segment( $url_offs + 1,'') == 'diff' ) {
      $this->diff( $page_name, $this->uri->segment(4) );
      return;
    }
    if( $this->uri->segment( $url_offs + 1,'') == 'raw' ) {
			$raw = true;
    }
		
		// handle data submission
    if( $this->input->post('save') && $this->wiki_auth->logged_in()) {
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
			if( $this->wiki_auth->logged_in() ) {
	      $editing = true;				
			} else {
				// show an error
	      $page->body = '<p class="error">' . lang('page_missing') . '</p>';
			}
    } else {
      if( !$editing ) {
				$parser = $this->config->item('wiki_parser','wiki_settings');
				if( $raw ) {
					$parser = 'raw';
				}
				$page->body = $this->ciwiki_parser->parse( $page->body, $parser );
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
			'page_title' => 'CI-Wiki - ' . lang('history') . ':' . $page->title
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
			'page_title' => 'CI-Wiki - ' . lang('revision') . ':' . $page_name
		);

		$this->load->view('layouts/standard_page', $pg_data );
			
	}

	// show recent changes
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
			'page_title' => 'CI-Wiki - ' . lang('recent_changes')
		);

		$this->load->view('layouts/standard_page', $pg_data );			
	}


	// show site index
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
			'page_title' => 'CI-Wiki - ' . lang('site_index')
		);

		$this->load->view('layouts/standard_page', $pg_data );			
	}

	// login
	protected function login()
	{
		if( $this->input->post('username') && $this->input->post('password')) {
			if( $this->wiki_auth->login($this->input->post('username'),$this->input->post('password'))) {
				redirect('/wiki');
			}
		}
		
    $view_data = array(
      'errors' => ''
      );

		$content = $this->load->view('wiki/ciwiki_login', $view_data, true );	

		$pg_data = array(
			'content' => $content,
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - ' . lang('login')
		);

		$this->load->view('layouts/standard_page', $pg_data );			
	}



	// show site index
	protected function search()
	{
		$results = array();
		if( $this->input->post('query')) {
			$results = $this->wiki_model->search( $this->input->post('query'))->result();
		}
		
		
    $view_data = array(
			'results' => $results,
      'errors' => ''
      );

		$content = $this->load->view('wiki/ciwiki_search', $view_data, true );	

		$pg_data = array(
			'content' => $content,
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - ' . lang('search')
		);

		$this->load->view('layouts/standard_page', $pg_data );			
	}
	
	protected function mk_nav()
	{
		$nav = '<h3>Toolbox</h3>';
		$nav .= '<ul class="vertical-nav">';
		$nav .= '<li><a href="' . site_url() .'/wiki">' . lang('wiki_home') . '</a></li>';
		//$nav .= '<li><a href="' . site_url() .'">what links here</a></li>';
		$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/changes">' . lang('recent_changes') . '</a></li>';
		$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/index">' . lang('site_index') . '</a></li>';
		$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/search">' . lang('search') . '</a></li>';
		
		if( $this->wiki_auth->logged_in()) {
			$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/logout">' . lang('logout') . '</a></li>';
	  } else {
			$nav .= '<li><a href="' . site_url() .'/wiki/ciwiki/login">' . lang('login') . '</a></li>';
		}
		
		$nav .= '</ul>';
		return $nav;
	}

}
