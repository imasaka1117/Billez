<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 匯出業者報表</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$date_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">匯出業者報表</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*業者</td>
				<td><select id="trader" class="required"></select></td>
			</tr>
			<tr>
				<td>*業者合約</td>			
				<td><select id="trader_contract" class="required"><option value="">請選擇</select></td>
			</tr>
			<tr>
				<td>*開始日期 :</td>
				<td>年 : <select id="begin_year" class="required"></select><br />
					月 : <select id="begin_month" class="required"></select><br />
					日 : <select id="begin_day" class="required"><option value="">請選擇 日</option></select></td>
			</tr>
			<tr>
				<td>*結束日期 :</td>
				<td>年 : <select id="end_year" class="required"></select><br />
					月 : <select id="end_month" class="required"></select><br />
					日 : <select id="end_day" class="required"><option value="">請選擇 日</option></select></td>
			<td><input id="export_btn" type="button" value="匯出Pdf" /></td>
			</tr>	
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>