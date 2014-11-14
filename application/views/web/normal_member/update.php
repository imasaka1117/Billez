<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 一般會員資料</title>
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
		<p class="title_p">一般會員資料</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>會員編號 : </td>	
				<td><span id="id" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>業者 : </td>	
				<td><span id="trader_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單種類 : </td>	
				<td><span id="bill_kind_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>辨識資料: </td>	
				<td><span id="identify_data" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>名稱 : </td>	
				<td><span id="normal_name" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>資料一 : </td>	
				<td><span id="data1" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>資料二 : </td>	
				<td><span id="data2" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>資料三 : </td>	
				<td><span id="data3" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>資料四 : </td>	
				<td><span id="data4" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>資料五 : </td>	
				<td><span id="data5" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>是否為行動會員 : </td>	
				<td><span id="action_member_identity" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>有訂閱的行動會員 : </td>	
				<td><select id="subscribe" multiple="multiple" style="width: 300px; height: 200px"></select></td>	
			</tr>
		</table>
		<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>