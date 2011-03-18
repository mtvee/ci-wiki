<h3><?= lang('recent_changes') ?></h3>

<table style="width: 100%">
	<tr>
		<th><?=lang('page')?></th>
		<th><?=lang('date')?></th>
		<th><?=lang('author')?></th>
	</tr>
<?php
 	$count = 0;
	foreach( $changes->result() as $page ) { ?>
	<tr class="<?= ($count % 2) == 0 ? '' : 'odd'?>">
		<td style="width: 65%"><a href="<?=site_url()?>/wiki/<?=$page->title?>"><?=$page->title?></a></td>
		<td><?=$page->created_on?></td>
		<td><?=$page->user?></td>
	</tr>
<?php $count++; } ?>
</table>