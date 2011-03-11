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

</style>
</head>
<body>

<h3><?=$page->title?></h3>
<div style="float: right; width: 30%; background-color: #eee;">
  <table cellpadding="5px">
    <tr>
      <td>h#. <em>text</em></td>
      <td>headings &lt;h#&gt;</td>
    </tr>
    <tr>
      <td>_<em>text</em>_</td>
      <td>italic &lt;em&gt;</td>
    </tr>
    <tr>
      <td>*<em>text...</em>*</td>
      <td>bold &lt;strong&gt;</td>
    </tr>
    <tr>
      <td>+<em>text...</em>+</td>
      <td>underline &lt;u&gt;</td>
    </tr>
    <tr>
      <td>pre. <em>text...</em></td>
      <td>preformatted &lt;pre&gt;</td>
    </tr>
    <tr>
      <td>"<em>text</em>":<em>url</em></td>
      <td>link</td>
    </tr>
    <tr>
      <td>!<em>url</em>!</td>
      <td>image</td>
    </tr>
    <tr>
      <td>*<em>text</em></td>
      <td>bullet (unordered) list</td>
    </tr>
    <tr>
      <td>#<em>text</em></td>
      <td>numbered (ordered) list</td>
    </tr>
  </table>
</div>
<?= $errors ?>
<form method="post" action="<?=site_url()?>/wiki/<?=$page->title?>">
  <input type="hidden" name="id" value="<?=$page->id?>" />
  <input name="title" value="<?=$page->title?>" /><br/>
  <textarea class="mceNoEditor" name="bodytext" cols="70" rows="20"><?=$page->body?></textarea><br/>
  <input class="ok" type="submit" name="save" value="Save" />
  <input type="submit" name="cancel" value="Cancel" />
</form>