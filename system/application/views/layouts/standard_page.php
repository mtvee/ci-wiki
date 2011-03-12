<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="author" content="J Knight - CI-Wiki (https://github.com/mtvee/ci-wiki)" />
	<meta name="generator" content="CI-Wiki (https://github.com/mtvee/ci-wiki)" />
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<!-- styles -->
  <link rel="stylesheet" href="<?=base_url()?>/css/style.css" type="text/css" />

	<!-- javascript -->
	
  <title><?= $page_title ?></title>
</head>
<body>
	<div class="wrap">

		<div id="left-column">
			<h2>CI-Wiki</h2>
			<?= $nav ?>
		</div> <!-- //left-column -->

		<div id="main-column">
			<?= $content ?>
		</div> <!-- //main-column -->
		
		
		<div id="footer">
			&copy; <?=date('Y')?> J. Knight | <a href="http://github.com/mtvee/ci-wiki">CI-Wiki</a> | {elapsed_time}</a>
		</div> <!-- //footer -->
	</div> <!-- //wrap -->
</body>
</html>