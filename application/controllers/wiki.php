<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		
		// this path
		$this->wiki_path = site_url() . '/wiki';
		
		// the namespace separator
		$this->namespace_sep = '::';

		// language stuffs
		$this->lang->load('ciwiki','english');
		$this->load->helper('language');
		
		$this->load->library('wiki_auth');		
    $this->load->model('wiki_model');
		$this->load->library('ciwiki_parser');
		
		// set the link format to point at our controller
		$this->ciwiki_parser->link_format = site_url() . '/wiki/%s';
		// set a callback function to detemine is a page exists
		$this->ciwiki_parser->set_link_check_cb( array(&$this->wiki_model, 'page_exists') );
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
		$tmp = explode( $this->namespace_sep, $page_name );
		if( $tmp[count($tmp)-1] == '' ) {
			$tmp[count($tmp)-1] = 'Index';
		}
		
		$namespace = implode( $this->namespace_sep, array_slice($tmp, 0, count($tmp)-1));
		$page_name = $tmp[count($tmp)-1];
		$page_path = $page_name;
		if( trim($namespace) != '') {
			$page_path = implode($this->namespace_sep, array( $namespace, $page_name));
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
		
    if( $this->uri->segment( $url_offs + 1,'') == 'media' && $this->wiki_auth->logged_in()) {
      $this->media( $page_name, $page_path );
      return;
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
			if( trim($this->input->post('namespace')) != '') {
	      $title = implode($this->namespace_sep, array($this->input->post('namespace'), $this->input->post('title')));				
			}
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
    $page = $this->wiki_model->get_page( $page_path );
    
		// page is empty, so edit it automatically
    if( !$page ) {
      $page = new StdClass();
      $page->id = -1;
      $page->title = $page_name;
			$page->namespace = $namespace;
      $page->body = '';
			if( $this->wiki_auth->logged_in() ) {
	      $editing = true;				
			} else {
				// show an error
	      $page->body = '<p class="error">' . lang('page_missing') . '</p>';
			}
    } else {
			$page->namespace = $namespace;
      if( !$editing ) {
				$parser = $this->config->item('wiki_parser','wiki_settings');
				if( $raw ) {
					$parser = 'raw';
				}
				$page->body = $this->ciwiki_parser->parse( $page->body, $page_path, $parser );
      }
    }
		
		// data for the view
		$view_data = array(
      'page' => $page,
			'page_name' => $page_name,
			'namespace' => $namespace,
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

	// handle media
	protected function media( $page_name, $page_path )
	{
		$page = $this->wiki_model->get_page( $page_path );
		// make sure there is actually a page here
		if( !$page ) {
			redirect( $this->wiki_path );
		}
		
		if( $this->input->post('upload')) {
			$form_name = 'media';
			if( isset($_FILES[$form_name]) && $_FILES[$form_name]['error'] == UPLOAD_ERR_OK ) {
				$file_info = new StdClass();
				$file_info->file_name = $_FILES[$form_name]['name'];
				$file_info->tmp_name = $_FILES[$form_name]['tmp_name'];
				$file_info->size = $_FILES[$form_name]['size'];
				$file_info->type = $_FILES[$form_name]['type'];				
				
				$tmp = $file_info->tmp_name;
				$fp = fopen( $tmp, 'r' );
				$file_info->data = fread( $fp, filesize( $tmp ));
				fclose( $fp );
				
				$this->wiki_model->add_media( $file_info->file_name, $page_name, $file_info->type, $file_info->size, $file_info->data );
				
			} else {
				if( $_FILES[$form_name]['error'] != 0 ) {
					switch($_FILES[$form_name]['error']) {
						// http://php.net/manual/en/features.file-upload.errors.php
					}
				}
				var_dump( $_FILES );
			}
		}
		
    $view_data = array(
			'title' => $page_name,
			'media' => $this->wiki_model->get_media_refs( $page_name ),
			'page' => $page,
      'errors' => ''
      );

		$content = $this->load->view('wiki/page_media', $view_data, true );	

		$pg_data = array(
			'content' => $content,
			'nav' => $this->mk_nav(),
			'page_title' => 'CI-Wiki - ' . lang('media') . ':' . $page_name
		);

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

	// used by site_index to parse the page names into a tree like assoc array
	protected function mk_tree( $ra, $delim )
	{
		$out = array();
		$re = '/' . preg_quote( $delim, '/') . '/';
		foreach( $ra as $key => $val ) {
			$parts = preg_split( $re, $key, -1, PREG_SPLIT_NO_EMPTY );
			$leaf = array_pop( $parts );
			$parr = &$out;
			foreach( $parts as $part ) {
				if( !isset($parr[$part])) {
					$parr[$part] = array('path'=>$part);
				}
				elseif( !is_array($parr[$part])) {
					$parr[$part] = array('path' => $parr[$part]);
				} 
				$parr = &$parr[$part];
			}
			
			if( empty($parr[$leaf])) {
				$parr[$leaf] = $val;
			} elseif( is_array($parr[$leaf])) {
				$parr[$leaf]['path'] = $val;
			}
		}
		return $out;
	}
	
	// used by site_index to render the page tree
	protected function render_tree( $tree, $out = '', $indent = 0 )
	{
		$out .= '<ul class="tree">';
		foreach( $tree as $k => $v ) {
			if( $k == 'path') {
				continue;
			}
			$val = (is_array($v) ? $v['path'] : $v );
			$out .= str_repeat(' ', $indent );
			$out .= '<li><a href="' . site_url() . '/wiki/' . $val . '">' . $k . "</a></li>\n";
			if( is_array($v) ) {
				$out = $this->render_tree( $v, $out, $indent + 4 );
			}
		}
		$out .= '</ul>';
		return $out;
	}

	// show site index
	protected function site_index()
	{
		$ndx = $this->wiki_model->site_index();
		
		$names = array();
		foreach( $ndx->result() as $page ) {
			$names[$page->title] = $page->title;
		}
		$tree = $this->mk_tree( $names, $this->namespace_sep );
		//var_dump( $tree );
		//echo $this->render_tree( $tree );
		
    $view_data = array(
			'pages' => $ndx->result(),
			'tree' => $this->render_tree( $tree ),
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

	// search
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
		$nav = '<h3>' . lang('toolbox') . '</h3>';
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
