<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a very simple authentication class. It uses user info
 * from 'wiki_settings.php' but one could easily drop in a proper
 * authentication system like ion_auth and use that instead.
 */
class wiki_auth
{
	// the codeigniter instance
	protected $ci;
	
	// CTOR
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->library( 'session' );
		$this->ci->load->config( 'wiki_settings', true );
	}
	
	// check for a valid login and return true/false
	function login( $username, $password, $remember = false)
	{
		// this simply grabs info from 'wiki_config.php' and uses that.
		// $config['wiki_admin_user'] = array('username'=>'admin','password'=>'letmein');
		$admin_user = $this->ci->config->item('wiki_admin_user','wiki_settings');
		if( $username == $admin_user['username'] && $password == $admin_user['password'] ) {
			$this->ci->session->set_userdata( 'username', $username );
			$this->ci->session->set_userdata( 'user_id', 1 );
			return true;
		}
		return false;
	}
	
	// unset the session data
	function logout()
	{
		$this->ci->session->unset_userdata('username');
		$this->ci->session->unset_userdata('user_id');
		$this->ci->session->sess_destroy();
	}
	
	// return true if the current user is logged in
	function logged_in()
	{
		return (bool)$this->ci->session->userdata('username');
	}
	
}

?>