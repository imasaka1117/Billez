<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢操作紀錄</title>
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
		<p class="title_p">查詢操作紀錄</p>
		<table cellpadding="10">
			<tr>
				<td>操作種類 : </td>	
				<td><select id="operator"></select></td>
				<td>資料表 : </td>	
				<td><select id="table"></select></td>
			</tr>
			<tr>
				<td>操作者 : </td>	
				<td><select id="user"></select></td>
				<td>時間 : </td>	
				<td><select id="time"></select></td>
				<td></td>
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>