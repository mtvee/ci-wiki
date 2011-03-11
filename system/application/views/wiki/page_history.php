
<div id="wiki-tools">
  <a href="<?=site_url()?>/wiki/<?=$page->title?>" title="View Page">back</a> |
  <a href="<?=site_url()?>/wiki/" title="Wiki Home">home</a>
</div>
  
<h3>Revisions for: <?=$page->title?></h3>

<ul>
<?php
foreach( $revisions as $rev ) { ?>

 <li><a href="<?=site_url()?>/wiki/<?=$page->title?>/diff/<?=$rev->id?>"><?=$rev->created_on?></a> by <?= $rev->user ?></li>

<?php
} ?>
</ul>
