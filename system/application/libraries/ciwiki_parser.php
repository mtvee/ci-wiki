<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ciwiki_parser
{
	// this will run thru sprinf
	public $link_format = '%s';
	
	// CTOR
	function __construct(  )
	{
	}
	
	function parse( $text, $dialect = 'textile' )
	{
		switch( $dialect ) {
			case 'textile':
			  include_once('parser_dialects/textile.php');
				$textile = new Textile;
				$textile->hu = '/wiki/';
			  $html = $textile->TextileThis($text);
			  // grab wiki links
			  return $this->wikify_links($html);
				break;
			case 'creole':
		  	include_once('parser_dialects/creole.php');
				$c = new creole(array('link_format' => $this->link_format ));
				return $c->parse($text);			
				break;
			case 'texy':
	  		include_once('parser_dialects/texy.min.php');
				$texy = new Texy();
				$html = $texy->process( $text );
			  // grab wiki links (FIXME this is broken for some reason)
			  return $this->wikify_links($html);
				break;
			case 'raw':
				return '<pre>' . $text . '</pre>';
				break;
			default:
				return $text;
				break;
		}
	}
	
	function wikify_links( $text )
	{
		$text = preg_replace_callback("/\[\[([\w\s\:\-]+)\]\]/U", array(&$this,'wiki_link'), $text );
	  $text = preg_replace_callback("/\[\[([\w\s\:\-]+)\|(.*)\]\]/U", array(&$this,'wiki_link'), $text );
	  return $text;
	}
	
	function camelCaseWord($subject, $delimiters = ' _-')
	{
	  if (!is_string($subject)) {
	    return '';
	  }

	  $subject = preg_replace('/[\s]+/', ' ', $subject);

	  $subject = preg_split("/[$delimiters]/", $subject);

	  foreach ($subject as &$word) {
	    $word = preg_replace('/[[\W:]]/', '', $word);

	    if (preg_match('/^[A-Z]{0,5}$/', $word)) {
	      continue;
	    }
	    $word = ucfirst(strtolower($word));
	  }
	  $subject = implode('', $subject);

	  return $subject;
	}

	function camelCase($subject)
	{
		$ra = explode('::', $subject );
		foreach( $ra as &$r ) {
			$r = $this->camelCaseWord( $r );
		}
		return implode('::', $ra );
	}

	function wiki_link( $match )
	{	
		if( count($match) > 2 ) {
		  return "<a href='" . sprintf( $this->link_format, $this->camelCase($match[1])) . "'/>" . $match[2] . "</a>";		
		}
	  return "<a href='" . sprintf( $this->link_format, $this->camelCase($match[1])) . "'/>" . $match[1] . "</a>";		
	}

}

