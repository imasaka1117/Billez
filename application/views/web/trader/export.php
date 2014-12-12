<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 匯出業者資料</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">匯出業者資料</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<th>匯出種類 : </th>
				<td>業者<input type="radio" value="data" name="kind" /></td>
				<td>業者合約<input type="radio" value="contract" name="kind" /></td>
			</tr>
			<tr>
			</tr>	
		</table>
		<table id="export_table" cellpadding="10">
			<tr>
				<td></td>
				<td><span id="list_text"></span></td>
				<td></td>
				<td><span id="export_list_text"></span></td>
			</tr>
			<tr>
				<td></td>
				<td><select id="list" multiple="multiple" style="width: 200px; height: 200px"></select></td>
				<td><input type="button" id="all_e" value="全部匯出"/><br /><br />
					<input type="button" id="single_e" value="個別匯出"/><br /><br />
					<input type="button" id="single_b" value="個別返回"/><br /><br />
					<input type="button" id="all_b" value="全部返回"/></td>
				<td><select id="export_list" name="export_list[]" multiple="multiple" style="width: 200px; height: 200px" class="required"></select></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td><input id="export_btn" type="button" value="匯出Excel" /></td>
			</tr>	
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>