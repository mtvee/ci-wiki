
<div id="wiki-tools">
  <a href="<?=site_url()?>/wiki/" title="Wiki Home"><?=lang('home')?></a> | 
  <?php if( $this->wiki_auth->logged_in()) { ?>
  	<a href="<?=site_url()?>/wiki/<?=$page->title?>/edit" title="<?=lang('edit_tip')?>"><?=lang('edit')?></a> |
  	<a href="<?=site_url()?>/wiki/<?=$page->title?>/media" title="<?=lang('media_tip')?>"><?=lang('media')?></a> |
  <?php } else { ?>
	
	<?php } ?>
  <a href="<?=site_url()?>/wiki/<?=$page->title?>/history" title="<?=lang('history_tip')?>"><?=lang('history')?></a> |
  <a href="<?=site_url()?>/wiki/ciwiki/help" title="Wiki Help"><?=lang('help')?></a> 
</div>
  
<div class="wiki-page">
 <h3><?= $page->title ?></h3>
 <?= $page->body ?>
</div>

