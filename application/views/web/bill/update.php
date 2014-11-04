<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增帳單種類</title>
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
		<p class="title_p">新增帳單種類</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>批次碼 : </td>	
				<td><span id="batch_code" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單編號 : </td>	
				<td><span id="billez_code" style="color:red"></span></td>	
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
				<td>年度 : </td>	
				<td><span id="year" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>月份 : </td>	
				<td><span id="month" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單種類辨識資料 : </td>	
				<td><span id="identify_data" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單所有人 : </td>	
				<td><span id="bill_owner" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單資料欄位1 : </td>	
				<td><span id="data1" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單資料欄位2 : </td>	
				<td><span id="data2" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單資料欄位3 : </td>	
				<td><span id="data3" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單資料欄位4 : </td>	
				<td><span id="data4" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>帳單資料欄位5 : </td>	
				<td><span id="data5" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>發行時間 : </td>	
				<td><span id="publish_time" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>繳費期限 : </td>	
				<td><span id="due_time" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>繳費金額 : </td>	
				<td><span id="amount" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>最低繳費金額 : </td>	
				<td><span id="lowest_pay_amount" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>銀行手續費 : </td>	
				<td><span id="bank_charge" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>郵局手續費 : </td>	
				<td><span id="post_charge" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>超商手續費 : </td>	
				<td><span id="cvs_charge" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>銀行繳費條碼1 : </td>	
				<td><span id="bank_barcode1" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>銀行繳費條碼2 : </td>	
				<td><span id="bank_barcode2" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>銀行繳費條碼3 : </td>	
				<td><span id="bank_barcode3" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>郵局繳費條碼1 : </td>	
				<td><span id="post_barcode1" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>郵局繳費條碼2 : </td>	
				<td><span id="post_barcode2" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>郵局繳費條碼3 : </td>	
				<td><span id="post_barcode3" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>超商繳費條碼1 : </td>	
				<td><span id="cvs_barcode1" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>超商繳費條碼2 : </td>	
				<td><span id="cvs_barcode2" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>超商繳費條碼3 : </td>	
				<td><span id="cvs_barcode3" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>繳費狀態 : </td>	
				<td><span id="pay_state" style="color:red"></span></td>	
			</tr>
		</table>
		<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>