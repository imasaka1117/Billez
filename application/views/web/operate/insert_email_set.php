<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增電子郵件格式</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">新增電子郵件格式</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*設定名稱 : </td>
				<td><input type="text" id="form_name" class="required" /></td>
			</tr>
			<tr>
				<td>*郵件種類 : </td>
				<td><select id="form_kind" class="required"><option value="">請選擇</option><option value="1">忘記密碼</option><option value="2">電子帳單</option><option value="3">印刷業者</option><option value="4">問題回報</option></select></td>
			</tr>
			<tr>
				<td>*郵件伺服器名稱 : </td>
				<td><input type="text" id="server_name" class="required" /></td>
			</tr>
			<tr>
				<td>*郵件連接埠號 : </td>
				<td><input type="text" id="server_port" class="required,digits" /></td>
			</tr>
			<tr>
				<td>帳號 : </td>
				<td><input type="text" id="account" /></td>
			</tr>
			<tr>
				<td>密碼 : </td>
				<td><input type="text" id="password" /></td>
			</tr>
			<tr>
				<td>*寄件者郵件 : </td>
				<td><input type="text" id="send_email" class="required,email" /></td>
			</tr>
			<tr>
				<td>*寄件者姓名 : </td>
				<td><input type="text" id="send_name" class="required" /></td>
			</tr>
			<tr>
				<td>*主旨 : </td>
				<td><input type="text" id="subject" class="required" /></td>
			</tr>
			<tr>
				<td>*內文 : <br />在資料處換上<br />$var1,$var2 以此類推<br />忘記密碼 : 1個<br />問題回報 : 2個<br />印刷業者 : 1個<br />電子帳單 : 1個</td>
				<td><textarea id="body" rows="10" cols="50" class="required"></textarea></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>