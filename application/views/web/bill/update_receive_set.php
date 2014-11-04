<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 修改入帳帳單格式</title>
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
var id = 'receive';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">修改入帳帳單格式</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<p>左邊空格為開始的位元, 右邊空格為結束的位元</p>
		<p>不可只填寫一個</p>
		<table cellpadding="10">
			<tr>
				<th>繳費帳單匯入格式</th>
			</tr>
			<tr>
				<td>*業者 : </td>
				<td><select id="trader" class="required"></select></td>
				<td>*帳單種類 : </td>
				<td><select id="bill_kind" class="required"><option value="">請選擇</option></select></td>
			</tr>
			<tr>
				<td>年度 :</td>
				<td><input type="text" id="year" size="3" class="begin" />  ~  <input type="text" id="year2"  size="3" /></td>
				<td>月份 :</td>
				<td><input type="text" id="month" size="3" class="begin" />  ~  <input type="text" id="month2"  size="3" /></td>
			</tr>
			<tr>
				<td>帳單所有人 :</td>
				<td><input type="text" id="bill_owner" size="3" class="begin" />  ~  <input type="text" id="bill_owner2"  size="3" /></td>
				<td>帳單種類辨識資料 :</td>
				<td><input type="text" id="identify_data" size="3" class="begin" />  ~  <input type="text" id="identify_data2"  size="3" /></td>				
			</tr>
			<tr>
				<td>帳單資料欄位1 :</td>
				<td><input type="text" id="data1" size="3" class="begin" />  ~  <input type="text" id="data12"  size="3" /></td>
				<td>帳單資料欄位2 :</td>
				<td><input type="text" id="data2" size="3" class="begin" />  ~  <input type="text" id="data22"  size="3" /></td>			
			</tr>
			<tr>
				<td>帳單資料欄位3 :</td>
				<td><input type="text" id="data3" size="3" class="begin" />  ~  <input type="text" id="data32"  size="3" /></td>
				<td>帳單資料欄位4 :</td>
				<td><input type="text" id="data4" size="3" class="begin" />  ~  <input type="text" id="data42"  size="3" /></td>					
			</tr>
			<tr>
				<td>帳單資料欄位5 :</td>
				<td><input type="text" id="data5" size="3" class="begin" />  ~  <input type="text" id="data52"  size="3" /></td>		
			</tr>
		</table>
		<input type="button" id="insert_btn" value="修改" />
	</div>
<div id="query_div">
</div>
</body>
</html>