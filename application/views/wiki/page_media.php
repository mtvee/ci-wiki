
<div id="wiki-tools">
  <a href="<?=site_url()?>/wiki/<?=$page->title?>" title="<?=lang('back_tip')?>"><?=lang('back')?></a> |
  <a href="<?=site_url()?>/wiki/" title="<?=lang('home_tip')?>"><?=lang('home')?></a>
</div>

<h3><?=lang('media')?>: <?=$title?></h3>

<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Size</th>
		</tr>
	</thead>
	<tbody>
<?php
 	$count = 0;
	foreach( $media->result() as $ref ) { ?>
	<tr class='<?= ($count % 2 == 0) ? 'odd' : ''?>'>
		<td><?= $ref->blob_name ?></td>
		<td><?= $ref->blob_type ?></td>
		<td><?= $ref->blob_size ?></td>
	</tr>
<?php $count++; } ?>
  </tbody>
</table>

<hr/>

<form enctype="multipart/form-data" method="post">
  <!-- <input name="MAX_FILE_SIZE" value="102400" type="hidden"> -->
  <input name="media" type="file">
  <input value="<?=lang('upload')?>" name="upload" type="submit">
</form>
