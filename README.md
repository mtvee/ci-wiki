CI-Wiki
======

This is a very simple wiki written with CodeIgniter. It is meant to be
a demonstration and not *production* code; something that can be learned
from and possibly to build your own wiki. I converted my localhost braindump
wiki from DocuWiki to this so it is usable as is.

Another wiki, yeah, I know, but I couldn't find much for a simple wiki with 
CodeIgniter so I though I would put this up in the hope it may help someone 
just starting. Comments are welcome!
 
At the moment it is missing some very important things that you need to be 
aware of...

  * the authentication is limited to one user (check config/wiki_settings.php)
  * it requires MySQL for pages (it would be nice to optionally use disk too)
  * PHP 5+ only
  * Flat namespace
  * there is no documentation (erm...)

Setup
-----

 * put the code somewhere your web server can see it.
 * create a database and a user for the wiki to use. *tables will be automatically created so watch you don't nuke something if you use an existing db (see wiki_model.php)*
 * edit `/system/application/config/config.php` and set the server path
 * edit `/system/application/config/database.php` and set your mysql stuff 
 * edit `/system/application/config/autoload.php` and make sure 'database' library and 'url' helper are loaded
 * you should be able to visit `SITE_URL/index.php/wiki` and get to edit the index page


Notes
-----
 
There are two options for wiki parser. You can set
`$config['wiki_parser'] = 'textile';` in `config/wiki_settings.php`
to choose which one you want. Other parsers are easily added.

The options are:
  
  * 'markdown' - markdown (extra) with MediaWiki style links
  * 'textile' - a simple textile parser with MediaWiki style links
  * 'creole' - [Creole](http://www.wikicreole.org/) is a simple, more traditional wiki parser
  * 'texy'  - [Texy](http://texy.info/en/) is a markdown like formatter

The authentication is quite insecure. The password is readable, plain text.
You should replace the auth system with something like 
[Ion Auth](https://github.com/benedmunds/CodeIgniter-Ion-Auth) if you want
to get serious about this.

Mysql
-----

    $> mysql -u root -p
    mysql> CREATE DATABASE ciwiki;
    mysql> GRANT ALL ON ciwiki.* TO uciwiki@localhost IDENTIFIED BY 'yourpassword';