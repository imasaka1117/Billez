<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 行動會員資料</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var id = '<?=$id ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">行動會員資料</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>會員編號 : </td>	
				<td><span id="id" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>會員帳號 : </td>	
				<td><span id="email" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>會員姓氏 : </td>	
				<td><span id="last_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>會員姓名 : </td>	
				<td><span id="first_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>手機號碼 : </td>	
				<td><span id="mobile_phone" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳號狀態 : </td>	
				<td><span id="state" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>OS種類 : </td>	
				<td><span id="mobile_phone_id" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單備忘錄 : </td>	
				<td><select id="bill_memo" multiple="multiple" style="width: 300px; height: 200px"></select></td>	
			</tr>
			<tr>
				<td><input id="update_btn" type="button" value="密碼更改" /></td>	
			</tr>
		</table>
		<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>