<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 排程設定</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">排程設定</p>
		<table cellpadding="10">
			<tr>
				<th>FTP繳費帳單匯入排程 </th>
				<td>啟用狀態 : </td>
				<td><span id="pay" style="color: red"></span></td>
				<td><input type="button" id="pay_go" value="啟用"/></td>
				<td><input type="button" id="pay_stop" value="停用"/></td>
				<td>FTP暫時停用天數 : </td>
				<td><select id="stop" class="required"></select></td>
				<td><input type="button" id="stop_btn" value="暫停"/></td>
			</tr>
			<tr>
				<th>FTP入帳帳單匯入排程</th>	
				<td>啟用狀態 : </td>
				<td><span id="receive" style="color: red"></span></td>
				<td><input type="button" id="receive_go" value="啟用"/></td>
				<td><input type="button" id="receive_stop" value="停用"/></td>
			</tr>
			<tr>
				<th>推播未讀取會員排程</th>	
				<td>啟用狀態 : </td>
				<td><span id="push" style="color: red"></span></td>
				<td><input type="button" id="push_go" value="啟用"/></td>
				<td><input type="button" id="push_stop" value="停用"/></td>
			</tr>
			<tr>
				<th>可能帳單配對處理排程</th>	
				<td>啟用狀態 : </td>
				<td><span id="possible" style="color: red"></span></td>
				<td><input type="button" id="possible_go" value="啟用"/></td>
				<td><input type="button" id="possible_stop" value="停用"/></td>
			</tr>
			
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>