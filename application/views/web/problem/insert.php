<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增問題</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">新增問題</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*問題描述 : </td>
				<td><textarea id="problem" rows="10" cols="50" class="required"></textarea></td>
			</tr>
			<tr>
				<td>*問題範圍 : </td>
				<td><select id="scope" class="required"><option value="">請選擇</option><option value="1">行動會員</option><option value="2">一般會員</option><option value="3">業者</option><option value="4">代收機構</option></select></td>
			</tr>
			<tr>
				<td>*提問者帳號 : </td>
				<td><input type="text" id="email" class="required" /></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>