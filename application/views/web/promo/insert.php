<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增促銷優惠</title>
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
		<p class="title_p">新增促銷優惠</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*活動名稱 : </td>
				<td><input type="text" id="name" class="required" /></td>
			</tr>
			<tr>
				<td>*活動範圍 : </td>
				<td><select id="range" class="required"><option value="">請選擇</option>
									   <option value="1">行動會員</option>
									   <option value="2">業者</option>
									   <option value="3">代收業者</option></select></td>
			</tr>
			<tr>
				<td>*活動開始日 :</td>
				<td>年 : <select id="begin_year" class="required"></select><br />
					月 : <select id="begin_month" class="required"></select><br />
					日 : <select id="begin_day" class="required"><option value="">請選擇 日</option></select></td>
			</tr>
			<tr>
				<td>*活動終止日 :</td>
				<td>年 : <select id="end_year" class="required"></select><br />
					月 : <select id="end_month" class="required"></select><br />
					日 : <select id="end_day" class="required"><option value="">請選擇 日</option></select></td>
			</tr>
			<tr>
				<td>*優惠方式</td>
				<td><select id="way" class="required"><option value="">請選擇</option>
									 <option value="1">贈品</option>
									 <option value="2">現金減免</option>
									 <option value="3">點數</option></select></td>
			</tr>
			<tr>
				<td>*活動等級</td>
				<td><select id="level" class="required"><option value="">請選擇</option>
									 <option value="1">1</option>
									 <option value="2">2</option>
									 <option value="3">3</option>
									 <option value="4">4</option>
									 <option value="5">5</option></select></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>