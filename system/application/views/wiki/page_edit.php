
<h3><?=$page->title?></h3>

<?= $errors ?>
<form method="post" action="<?=site_url()?>/wiki/<?=$page->title?>">
  <input type="hidden" name="id" value="<?=$page->id?>" />
  <input name="title" value="<?=$page->title?>" /><br/>
  <textarea class="mceNoEditor" name="bodytext" cols="70" rows="20"><?=$page->body?></textarea><br/>
  <input class="ok" type="submit" name="save" value="Save" />
  <input type="submit" name="cancel" value="Cancel" />
</form>