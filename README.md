CI-Wiki
======

This is a very simple wiki written with codeigniter. It is meant to be
a demonstration and not *production* code. At the moment it is missing some 
very important things that you need to be aware of...

  * there is no authentication so the wiki is wide open. *Don't run this
    on a production server*
  * changes are stored (diffs) but not web viewable as of yet
  * it requires MySQL (it would be nice to use disk too)
  * PHP 5+ only
  * it uses a kludge'd textile formatting (real wiki formatting would be nicer)
  * there is no documentation (erm...)

Setup
-----

 * put the code somewhere your web server can see it.
 * create a database and a user for the wiki to use, tables will be automatically created so watch you don't nuke something if you use an existing db (see wiki_model.php)
 * edit '/system/application/config/config.php' and set the server path
 * edit '/system/application/config/database.php' and set your mysql stuff 
 * edit '/system/application/config/autoload.php' and make sure 'database' library and 'url' helper are loaded
 * you should be able to visit SITE_URL/index.php/wiki and get to edit the index page