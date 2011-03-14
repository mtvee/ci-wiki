<div id="wiki-tools">
  <a href="<?=site_url()?>/wiki/<?=$title?>/history" title="View Page">back</a> |
  <a href="<?=site_url()?>/wiki/<?=$title?>" title="View Page">page</a> |
  <a href="<?=site_url()?>/wiki/" title="Wiki Home">home</a>
</div>


<h3><?= $diff->title ?></h3>

<?php $lines = explode( "\n", $diff->body ); 
	foreach( $lines as $line ):
		if( strlen($line)) {
			if( $line[0] == '<' ) {
				echo '<span class="del">' . htmlspecialchars($line) . '</span>';
			}
			else if( $line[0] == '>' ) {
				echo '<span class="ins">' . htmlspecialchars($line) . '</span>';			
			} else {
				echo '<br/>' .  htmlspecialchars($line);			
			}
		} 
	endforeach;
?>