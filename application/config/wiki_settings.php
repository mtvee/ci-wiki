<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Wiki Parser
|--------------------------------------------------------------------------
| This is the wiki parser you wish to use for your pages.
| Currently the following parsers are available:
|
|  creole  - a simple wiki parser with a more traditiona wiki style
|  textile - a simple textile parser with addition to handle wiki links
|  texy - a simple markdown like parser with addition to handle wiki links
|  raw - returns text wrapped in pre tags
|
*/
$config['wiki_parser'] = 'markdown';

/*
|--------------------------------------------------------------------------
| Admin login
|--------------------------------------------------------------------------
| This is a plain vanilla user for site edits. Very simple, one user setup. 
| One should be able to drop Ino Auth or another system in with little 
| difficulty.
*/
$config['wiki_admin_user'] = array('username'=>'admin','password'=>'letmein');