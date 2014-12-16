<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 等級資料</title>
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
		<p class="title_p">等級資料</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>等級編號 : </td>	
				<td><span id="id" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>等級名稱 : </td>	
				<td><input type="text" id="name" class="required"/></td>	
			</tr>
		</table>
		<input type="button" id="update_btn" value="更改" />&nbsp;<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>