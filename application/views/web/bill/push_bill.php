<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 帳單推播</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
var function_name = '<?=$function_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">帳單推播</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*推播種類 : </td>	
				<td><select id="import_bill_kind" class="required"><option value="">請選擇</option><option value="1">繳費帳單</option></select></td>
<!-- 			<option value="2">入帳帳單</option> -->
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>