<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢行動會員</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">查詢行動會員</p>
		<table cellpadding="10">
			<tr>
				<td>會員編號 : </td>	
				<td><input id="id" type="text"/></td>		
				<td>會員姓 : </td>	
				<td><input id="last_name" type="text"/></td>
				<td>會員名 : </td>	
				<td><input id="first_name" type="text"/></td>
			</tr>
			<tr>
				<td>會員電子郵件 : </td>	
				<td><input id="email" type="text"/></td>
				<td>會員手機 : </td>	
				<td><input id="mobile_phone" type="text"/></td>
				<td>認證狀態 : </td>	
				<td><select id="state"><option value="">請選擇</option><option value="1">未認證</option><option value="2">完成註冊</option><option value="3">刪除</option><option value="4">黑名單</option></select></td>		
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>