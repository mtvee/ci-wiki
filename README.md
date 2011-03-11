CI-WIKI
======

This is a very simple wiki written with codeigniter. At the moment it is
missing some very important things that you need to be aware of...

  * there is no authentication so the wiki is wide open.
  * revisions are stored but not viewable
  * it requires MySQL

Setup
-----

 * put the code somewhere your web server can see it.
 * create a database and a user for the wiki to use
 * edit '/system/application/config/config.php' and set the server path
 * edit '/system/application/config/database.php' and set your mysql stuff 
 * edit '/system/application/config/autoload.php' and make sure 'database' library and 'url' helper are loaded
 * tables will be automatically created (see wiki_model.php)
