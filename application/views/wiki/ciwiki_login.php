<h3><?= lang('login') ?></h3>

<form method="post" name='login-form'>
  <label for="username"><?=lang('username')?></label><br/>
  <input name="username" size="20" /><br/>
  <label for="password"><?=lang('password')?></label><br/>
  <input name="password" type="password" size="20" /><br/>
 <button><?= lang('login') ?></button>
</form>

<script type="text/javascript" language="JavaScript">
	// put the cursor in the right spot
	document.forms['login-form'].elements['username'].focus();
</script>