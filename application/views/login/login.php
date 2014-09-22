<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?=$title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link type="text/css" rel="stylesheet" href="<?=base_url()?>resources/css/login.css" />
<script type="text/javascript" src="<?=base_url()?>resources/js/jQuery 1.11.0.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>resources/js/function.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	init_login();
	
	$("input").click(function() {
		switch ($(this).attr('id')) {
			case 'login_btn':
				if(!check_blank(new Array('login_email', 'login_password'))) return false;
				if(check_ajax('<?=base_url()?>login/aa', new Array('login_email', 'login_password'), new Array('帳號或密碼錯誤！！', ''))) window.location.href = '<?=base_url()?>/home'; 
				
				break;
		}
	});	
});

function init_login() {
	$.ajaxSetup({
		async: false	
	});
}
</script>
</head>
<body>
	<div id="content_div">
		<h2>Billez Server 後台管理系統</h2>
		<h2><?=$tip?></h2>
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