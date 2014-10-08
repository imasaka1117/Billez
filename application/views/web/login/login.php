<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?=$title ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="<?=base_url() ?><?=$css_path ?><?=$login_css ?>" />
<script type="text/javascript" src="<?=base_url() ?><?=$js_path ?><?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=base_url() ?><?=$js_path ?><?=$function_js ?>"></script>
<script type="text/javascript" src="<?=base_url() ?><?=$js_path ?><?=$login_js ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=base_url() ?><?=$index_url ?>';
</script>
</head>
<body>
	<div id="content_div">
		<h2>Billez Server 後台管理系統</h2>
		<h2>管理者帳號 : root 密碼 : root</h2>
		<table cellpadding="10">
			<tr>
				<th>*帳號(電子郵件) : </th>
				<td><input id="login_email" type="text"/></td>
			</tr>
			<tr>
				<th>*密碼 : </th>
				<td><input id="login_password" type="password" maxlength="15" /></td>
			</tr>
		</table>
		<input id="login_btn" class="btn_input" type="button" value="登入" />
	</div>
</body>
</html>