<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 帳單匯入錯誤資料</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
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
		<p class="title_p">帳單匯入錯誤資料</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>匯入時間 : </td>	
				<td><span id="time" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>匯入人員 : </td>	
				<td><span id="user" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>業者 : </td>	
				<td><span id="trader_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單種類 : </td>	
				<td><span id="bill_kind_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>匯入檔名 : </td>	
				<td><span id="file_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>匯入檔案路徑 : </td>	
				<td><span id="file_path" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>匯入種類 : </td>	
				<td><span id="kind" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>錯誤原因 : </td>	
				<td><span id="reason" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>*是否處理 : </td>	
				<td><select id="result" class="required"><option value="">請選擇</option><option value="y">已處理</option><option value="n">未處理</option></select></td>	
			</tr>
		</table>
		<input type="button" id="update_btn" value="更改" />&nbsp;<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>