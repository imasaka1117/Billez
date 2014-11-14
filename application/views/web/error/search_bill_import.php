<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢帳單匯入錯誤紀錄</title>
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
		<p class="title_p">查詢帳單匯入錯誤紀錄</p>
		<table cellpadding="10">
			<tr>
				<td>業者 : </td>	
				<td><select id="trader"></select></td>
				<td>帳單種類 : </td>	
				<td><select id="bill_kind"><option value="">請選擇</option></select></td>
				<td>匯入種類 : </td>	
				<td><select id="import_kind"></select></td>			
			</tr>
			<tr>
				<td>處理狀況 : </td>	
				<td><select id="state"></select></td>
				<td>匯入時間 : </td>	
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