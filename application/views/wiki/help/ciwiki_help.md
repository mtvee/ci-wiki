Ci-Wiki Help
------------

CI_Wiki is a simple wiki written with the CodeIgniter framework. It is
meant to be an illustration of several techniques for using the framework
but has evolved somewhat from the this goal into a somewhat useful personal 
wiki. 

Requirements
============

* mysql 5+
* php 5+



Parsers
=======

The wiki has the ability to utilize a few different parsers:

* markdown
* textile
* creole
* texy

Regardless of which processor you choose, wiki links and wiki media links
have the same syntax. You can use the parser syntax for external links.

Links
=====

Wiki page links are specified inside square brackets

&#91;[Page Path]]

or with text for a link as

&#91;[Page Path|link text]]

Media Files
===========

Media files are associated with a page and are referenced using curly 
braces and an exclamation mark

&#123;{!MediaName}}

You can specify the size of images with a question mark followed by width 
or width and height

&#123;{!ImageName?200x150}}

You can also specify downloadable content with 'download' or 'dl' after the
question mark

&#123;{!MediaName?download}}

In all cases you can follow with a vertical bar and text. In the case of
images the text will become the 'title' and in the case of downloadable
content, will become the link text.

&#123;{!MediaName?download|Get it here}}
