<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢促銷活動</title>
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
		<p class="title_p">查詢促銷活動</p>
		<table cellpadding="10">
			<tr>
				<td>活動編號 : </td>	
				<td><input id="id" type="text"/></td>		
				<td>活動名稱 : </td>	
				<td><input id="name" type="text"/></td>
			</tr>
			<tr>
				<td>活動範圍 : </td>	
				<td><select id="range"><option value="">請選擇</option>
									   <option value="1">行動會員</option>
									   <option value="2">業者</option>
									   <option value="3">代收業者</option></select></td>		
				<td>活動等級 : </td>	
				<td><select id="level"><option value="">請選擇</option>
									 <option value="1">1</option>
									 <option value="2">2</option>
									 <option value="3">3</option>
									 <option value="4">4</option>
									 <option value="5">5</option></select></td>
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>