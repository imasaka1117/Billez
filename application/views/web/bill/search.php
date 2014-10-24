<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢帳單</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">查詢帳單</p>
		<table cellpadding="10">
			<tr>
				<td>帳單編號 : </td>	
				<td><input id="billez_code" type="text"/></td>		
				<td>業者 : </td>	
				<td><select id="trader"></select></td>
				<td>帳單種類 : </td>	
				<td><select id="bill_kind"><option value="">請選擇</option></select></td>
			</tr>
			<tr>
				<td>帳單所有人 : </td>	
				<td><input id="owner" type="text"/></td>
				<td>年度 : </td>	
				<td><select id="year"></select></td>	
				<td>月份 : </td>	
				<td><select id="month"><option value="">請選擇</option></select></td>		
			</tr>
			<tr>
				<td>帳單辨識資料 : </td>	
				<td><input id="identify_data" type="text"/></td>		
				<td></td>
				<td></td>
				<td></td>
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>