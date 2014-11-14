<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢推播錯誤</title>
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
		<p class="title_p">查詢推播錯誤</p>
		<table cellpadding="10">
			<tr>
				<td>會員編號 : </td>	
				<td><input type="text" id="id" /></td>		
				<td>手機號碼 : </td>	
				<td><input type="text" id="mobile_phone" /></td>
			</tr>
			<tr>
				<td>推播原因 : </td>	
				<td><select id="event"></select></td>
				<td>時間 : </td>	
				<td><select id="time"></select></td>		
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
		<input id="pay_not_read_btn" type="button" value="推播未讀取繳費帳單的會員" />&nbsp;<input id="receive_not_read_btn" type="button" value="推播未讀取入帳帳單的會員" />
	</div>
<div id="query_div">
</div>
</body>
</html>