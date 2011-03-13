
<div id="wiki-tools">
  <?php if( $this->wiki_auth->logged_in()) { ?>
  	<a href="<?=site_url()?>/wiki/<?=$page->title?>/edit" title="Edit Page">edit</a> |
  <?php } else { ?>
	
	<?php } ?>
  <a href="<?=site_url()?>/wiki/<?=$page->title?>/history" title="Page History">history</a> |
  <a href="<?=site_url()?>/wiki/" title="Wiki Home">home</a>
</div>
  
<div class="wiki-page">
 <h3><?= $page->title ?></h3>
 <?= $page->body ?>
</div>

