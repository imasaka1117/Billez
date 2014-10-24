<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 管理系統</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="<?=$login_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>
</head>
<body>
	<div id="content_div">
		<h2>Billez Server 後台管理系統</h2>
		<h2>管理者帳號 : root@root 密碼 : root</h2>
		<table cellpadding="10">
			<tr>
				<th>*帳號(電子郵件) : </th>
				<td><input id="login_email" type="text" class="required,email" /></td>
			</tr>
			<tr>
				<th>*密碼 : </th>
				<td><input id="login_password" type="password" maxlength="15" class="required" /></td>
			</tr>
		</table>
		<input id="login_btn" class="btn_input" type="button" value="登入" />
	</div>
</body>
</html>