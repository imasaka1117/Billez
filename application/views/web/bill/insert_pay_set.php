<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增繳費帳單格式</title>
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
		<p class="title_p">新增繳費帳單格式</p>
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
				<td>發行時間 :</td>
				<td><input type="text" id="publish_time" size="3" class="begin" />  ~  <input type="text" id="publish_time2"  size="3" /></td>				
			</tr>
			<tr>
				<td>繳費期限 :</td>
				<td><input type="text" id="due_time" size="3" class="begin" />  ~  <input type="text" id="due_time2"  size="3" /></td>
				<td>繳費金額 :</td>
				<td><input type="text" id="amount" size="3" class="begin" />  ~  <input type="text" id="amount2"  size="3" /></td>				
			</tr>
			<tr>
				<td>最低繳費金額 :</td>
				<td><input type="text" id="lowest_pay_amount" size="3" class="begin" />  ~  <input type="text" id="lowest_pay_amount2"  size="3" /></td>
				<td>銀行手續費 :</td>
				<td><input type="text" id="bank_charge" size="3" class="begin" />  ~  <input type="text" id="bank_charge2"  size="3" /></td>				
			</tr>
			<tr>
				<td>郵局手續費 :</td>
				<td><input type="text" id="post_charge" size="3" class="begin" />  ~  <input type="text" id="post_charge2"  size="3" /></td>
				<td>超商手續費 :</td>
				<td><input type="text" id="cvs_charge" size="3" class="begin" />  ~  <input type="text" id="cvs_charge2"  size="3" /></td>				
			</tr>
			<tr>
				<td>銀行繳費條碼1 :</td>
				<td><input type="text" id="bank_barcode1" size="3" class="begin" />  ~  <input type="text" id="bank_barcode12"  size="3" /></td>
				<td>銀行繳費條碼2 :</td>
				<td><input type="text" id="bank_barcode2" size="3" class="begin" />  ~  <input type="text" id="bank_barcode22"  size="3" /></td>	
			</tr>
			<tr>
				<td>銀行繳費條碼3 :</td>
				<td><input type="text" id="bank_barcode3" size="3" class="begin" />  ~  <input type="text" id="bank_barcode32"  size="3" /></td>
				<td>郵局繳費條碼1 :</td>
				<td><input type="text" id="post_barcode1" size="3" class="begin" />  ~  <input type="text" id="post_barcode12"  size="3" /></td>				
			</tr>
			<tr>
				<td>郵局繳費條碼2 :</td>
				<td><input type="text" id="post_barcode2" size="3" class="begin" />  ~  <input type="text" id="post_barcode22"  size="3" /></td>
				<td>郵局繳費條碼3 :</td>
				<td><input type="text" id="post_barcode3" size="3" class="begin" />  ~  <input type="text" id="post_barcode32"  size="3" /></td>			
			</tr>
			<tr>
				<td>超商繳費條碼1 :</td>
				<td><input type="text" id="cvs_barcode1" size="3" class="begin" />  ~  <input type="text" id="cvs_barcode12"  size="3" /></td>
				<td>超商繳費條碼2 :</td>
				<td><input type="text" id="cvs_barcode2" size="3" class="begin" />  ~  <input type="text" id="cvs_barcode22"  size="3" /></td>				
			</tr>
			<tr>
				<td>超商繳費條碼3 :</td>
				<td><input type="text" id="cvs_barcode3" size="3" class="begin" />  ~  <input type="text" id="cvs_barcode32"  size="3" /></td>
				<td>繳費地點1 :</td>
				<td><input type="text" id="pay_place1" size="3" class="begin" />  ~  <input type="text" id="pay_place12"  size="3" /></td>	
			</tr>
			<tr>
				<td>繳費地點2 :</td>
				<td><input type="text" id="pay_place2" size="3" class="begin" />  ~  <input type="text" id="pay_place22"  size="3" /></td>
				<td>繳費地點3 :</td>
				<td><input type="text" id="pay_place3" size="3" class="begin" />  ~  <input type="text" id="pay_place32"  size="3" /></td>
				
			</tr>
			<tr>
				<td>繳費地點4 :</td>
				<td><input type="text" id="pay_place4" size="3" class="begin" />  ~  <input type="text" id="pay_place42"  size="3" /></td>
				<td>繳費地點5 :</td>
				<td><input type="text" id="pay_place5" size="3" class="begin" />  ~  <input type="text" id="pay_place52"  size="3" /></td>
			</tr>
			<tr>
				<td>過期繳費地點1 :</td>
				<td><input type="text" id="overdue_pay_place1" size="3" class="begin" />  ~  <input type="text" id="overdue_pay_place12"  size="3" /></td>
				<td>過期繳費地點2 :</td>
				<td><input type="text" id="overdue_pay_place2" size="3" class="begin" />  ~  <input type="text" id="overdue_pay_place22"  size="3" /></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>