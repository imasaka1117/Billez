<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 更改系統設定</title>
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
		<p class="title_p">更改系統設定</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>設定名稱 : </td>
				<td><span id="name" style="color: red"></span></td>
			</tr>
			<tr>
				<td>*啟用狀態 : </td>
				<td><select id="using" class="required"><option value="">請選擇</option><option value="y">啟用</option><option value="n">不啟用</option></select></td>
			</tr>
			<tr>
				<td>*推播次數上限 : </td>
				<td><select id="push_times" class="required"></select></td>
			</tr>
			<tr>
				<td>*認證簡訊次數上限 : </td>
				<td><select id="sms_times" class="required"></select></td>
			</tr>
			<tr>
				<td>*抓取FTP檔案及匯入間隔時間 : </td>
				<td><select id="get_file_time" class="required"></select></td>
			</tr>
			<tr>
				<td>*配對可能帳單間隔時間 : </td>
				<td><select id="possible_bill_time" class="required"></select></td>
			</tr>
			<tr>
				<td>*整理錯誤清單間隔時間 : </td>
				<td><select id="error_list_time" class="required"></select></td>
			</tr>
			<tr>
				<td>*重複推播間隔時間 : </td>
				<td><select id="repeat_push_time" class="required"></select></td>
			</tr>
		</table>
		<input type="button" id="update_btn" value="更改" />&nbsp;<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>