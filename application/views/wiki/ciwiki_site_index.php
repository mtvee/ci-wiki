<h3><?=lang('site_index')?></h3>

<table style="width: 100%">
	<tr>
		<th><?=lang('page')?></th>
	</tr>
<?php
 	$count = 0;
	foreach( $pages->result() as $page ) { ?>
	<tr class="<?= ($count % 2) == 0 ? '' : 'odd'?>">
		<td><a href="<?=site_url()?>/wiki/<?=$page->title?>"><?=$page->title?></a></td>
	</tr>
<?php $count++; } ?>
</table>