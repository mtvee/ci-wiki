<h3>Recent Changes</h3>

<table style="width: 100%">
	<tr>
		<th>Page</th>
		<th>Revised</th>
		<th>By</th>
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