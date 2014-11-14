<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢電子郵件錯誤</title>
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
		<p class="title_p">查詢電子郵件錯誤</p>
		<table cellpadding="10">
			<tr>
				<td>會員編號 : </td>
				<td><input type="text" id="id" /></td>
				<td>電子郵件 : </td>
				<td><input type="text" id="email" /></td>
			</tr>
			<tr>
				<td>寄發原因 : </td>
				<td><select id="event"></select></td>
				<td>時間 : </td>
				<td><select id="time"></select></td>
				<td><input type="button" id="search_btn" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>