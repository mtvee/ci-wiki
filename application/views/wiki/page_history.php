
<div id="wiki-tools">
  <a href="<?=site_url()?>/wiki/<?=$page->title?>" title="<?=lang('back_tip')?>"><?=lang('back')?></a> |
  <a href="<?=site_url()?>/wiki/" title="<?=lang('home_tip')?>"><?=lang('home')?></a>
</div>
  
<h3><?=lang('revision')?>: <?=$page->title?></h3>

<ul>
<?php
foreach( $revisions as $rev ) { ?>

 <li><a href="<?=site_url()?>/wiki/<?=$page->title?>/diff/<?=$rev->id?>"><?=$rev->created_on?></a> by <?= $rev->user ?></li>

<?php
} ?>
</ul>
