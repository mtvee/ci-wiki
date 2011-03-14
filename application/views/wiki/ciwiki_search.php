<h3>Search</h3>

<form method="post">
	<input name="query" value="" size="50" />
	<button>Go</button>
</form>

<hr/>

<table style="width: 100%">
<?php
 	$count = 0;
	foreach( $results as $page ) { ?>
	<tr class="<?= ($count % 2) == 0 ? '' : 'odd'?>">
		<td><a href="<?=site_url()?>/wiki/<?=$page->title?>"><?=$page->title?></a></td>
	</tr>
<?php $count++; } ?>
</table>