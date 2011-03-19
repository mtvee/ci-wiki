<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Library class to handle parsing and parsing dialects
class ciwiki_parser
{
	// this will run thru sprinf
	public $link_format = '%s';
	
	// CTOR
	function __construct(  )
	{
		$this->ci = get_instance();
	}
	
	// add your parser in here
	function parse( $text, $dialect = 'textile' )
	{
		
		switch( $dialect ) {
			case 'textile':
			  include_once('parser_dialects/textile.php');
				$textile = new Textile;
				$textile->hu = '/wiki/';
			  // grab wiki links
			  $html = $this->wikify_links($text);
			  return $textile->TextileThis( $html );
				break;
			case 'creole':
				// TODO figure out how to color missing links with this thing
		  	include_once('parser_dialects/creole.php');
				$c = new creole(array('link_format' => $this->link_format ));
				return $c->parse($text);			
				break;
			case 'markdown':
	  		include_once('parser_dialects/markdown.php');
			  // grab wiki links
			  $html = $this->wikify_links($text);
				return Markdown( $html );
				break;
			case 'texy':
	  		include_once('parser_dialects/texy.min.php');
				$texy = new Texy();
			  // grab wiki links
			  $html = $this->wikify_links($text);
				return $texy->process( $html );
				break;
			case 'raw':
				return '<pre>' . $text . '</pre>';
				break;
			default:
				return $text;
				break;
		}
	}
	
	// deal with MediaWiki style links for local links.
	// This keeps the local link stuff consistant across dialects
	function wikify_links( $text )
	{
		$text = preg_replace_callback("/\[\[([\w\s\p{L}:-]+)\]\]/Uu", array(&$this,'wiki_link'), $text );
	  $text = preg_replace_callback("/\[\[([\w\s\p{L}:-]+)\|(.*)\]\]/Uu", array(&$this,'wiki_link'), $text );
	  return $text;
	}
	
	// CamelCase a word
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

	// splits words on namespace '::', CamelCases the words and sticks
	// thigs back together
	function camelCase($subject)
	{
		$ra = explode('::', $subject );
		foreach( $ra as &$r ) {
			$r = $this->camelCaseWord( $r );
		}
		return implode('::', $ra );
	}

	// callback from preg_replace in '$this->wikify_links'
	function wiki_link( $match )
	{	
		$css_class = '';
		$link = sprintf( $this->link_format, $this->camelCase($match[1]));
		
		if( !$this->ci->wiki_model->get_page( $this->camelCase($match[1]) )) {
			$css_class = 'missing';
		}
		
		if( count($match) > 2 ) {
		  return "<a class='$css_class' href='$link'/>" . $match[2] . "</a>";		
		}
	  return "<a class='$css_class' href='$link'/>" . $match[1] . "</a>";		
	}

}

