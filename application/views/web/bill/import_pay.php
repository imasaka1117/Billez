<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 繳費帳單匯入</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$ajax_file_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$date_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
var function_name = '<?=$function_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">繳費帳單匯入</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*業者 : </td>
				<td><select id="trader" class="required"></select></td>
				<td>*帳單種類 : </td>
				<td><select id="bill_kind" class="required"><option value="">請選擇</option></select></td>
			</tr>
		</table>
		<table id="import_table" cellpadding="10">
			<tr>
				<td>*匯入帳單(csv格式) : </td>
				<td><input type="file" name="bill_file" id="bill_file" class="required" />&nbsp;<input type="button" id="import_btn" value="匯入帳單" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>