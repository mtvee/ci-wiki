
<div id="wiki-tools">
  <a href="<?=site_url()?>/wiki/<?=$page->title?>/edit" title="Edit Page">edit</a> |
  <a href="<?=site_url()?>/wiki/<?=$page->title?>/history" title="Page History">history</a> |
  <a href="<?=site_url()?>/wiki/" title="Wiki Home">home</a>
</div>
  
<div class="wiki-text">
<h3><?= $page->title ?></h3>
<?= $page->body ?>
</div>

