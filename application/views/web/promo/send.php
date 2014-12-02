<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 寄發促銷優惠</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$date_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">寄發促銷優惠</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>請選擇要匯出csv檔的活動範圍</td>
			</tr>
			<tr>
				<td>*活動範圍 : </td>
				<td><select id="range" class="required"><option value="">請選擇</option>
									   <option value="1">行動會員</option>
									   <option value="2">業者</option>
									   <option value="3">代收業者</option></select></td>
				<td><input type="submit" id="export_btn" value="匯出" /></td>
			</tr>
		</table>
		
	</div>
<div id="query_div">
</div>
</body>
</html>