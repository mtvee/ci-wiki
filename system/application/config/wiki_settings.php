<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Wiki Parser
|--------------------------------------------------------------------------
| This is the wiki parser you wish to use for your pages.
| Currently the following parsers are available:
|  creole  - a simple wiki parser with a more traditiona wiki style
|  textile - a simple textile parser with addition to handle wiki links
|
*/
$config['wiki_parser'] = 'textile';

/*
|--------------------------------------------------------------------------
| Admin login
|--------------------------------------------------------------------------
| This is a plain vanilla user for site edits. Very simple, one user setup.
*/
$config['wiki_admin_user'] = array('username'=>'admin','password'=>'letmein');