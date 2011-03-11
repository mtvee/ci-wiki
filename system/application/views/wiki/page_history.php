<html>
<head>
<title>Welcome to CI-Wiki</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

a {
	text-decoration: none;
}

#wiki-tools {
	float: right;
}


</style>
</head>
<body>
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
