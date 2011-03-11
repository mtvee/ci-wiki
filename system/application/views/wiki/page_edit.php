
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