<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 更改業者合約</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$date_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use2 ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var id = '<?=$id ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">更改業者合約</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>代收機構合約編號 : </td>
				<td><span id="id" style="color: red"><?=$id ?></span></td>
			</tr>
			<tr>
				<td>代收機構名稱 :</td>
				<td><input type="text" id="machinery" disabled="disabled" /></td>	
			</tr>
			<tr>
				<td>*合約名稱 :</td>
				<td><input type="text" id="contract_name" size="20" class="required" /></td>	
			</tr>
			<tr>
				<td>廣告網址 :</td>
				<td><input type="text" id="ad_url" size="60"/></td>
			</tr>
			<tr>
				<td>*合約年限 :</td>
				<td><select id="contract_age" class="required"></select></td>
			</tr>
			<tr>
				<td>*合約開始日 :</td>
				<td>年 : <select id="begin_year" class="required"></select><br />
					月 : <select id="begin_month" class="required"></select><br />
					日 : <select id="begin_day" class="required"><option value="">請選擇 日</option></select></td>
			</tr>
			<tr>
				<td>*合約終止日 :</td>
				<td>年 : <select id="end_year" class="required"></select><br />
					月 : <select id="end_month" class="required"></select><br />
					日 : <select id="end_day" class="required"><option value="">請選擇 日</option></select></td>
			</tr>
			<tr>
				<td>*帳單費用 :</td>
				<td><select id="bill_cost" class="required">
						<option value="">請選擇 種類</option>
						<option value="1">月租</option>
						<option value="2">件計</option></select>
			</tr>
			<tr>
				<td>*付款時間 :</td>
				<td><select id="pay" class="required">
						<option value="">請選擇</option>
						<option value="1">每週</option>
						<option value="2">每月</option>
						<option value="3">每年</option>
						<option value="4">不固定</option></select>
			</tr>
			<tr>	
				<td>合約備註 :</td>
				<td><textarea id="contract_remark" rows="5" cols="45"></textarea></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="更改" /> <input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>