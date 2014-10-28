<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增業者合約</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$date_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>2"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">新增業者合約</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<th>業者合約</th>
			</tr>
			<tr>
				<td>*業者名稱 :</td>
				<td><select id="trader" class="required"></select></td>	
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
				<td>*帳單種類 :</td>
				<td><select id="bill_kind" class="required"></select></td>
			</tr>
			<tr>
				<td>*帳單依據 :</td>
				<td><select id="bill_basis" class="required"></select></td>
			</tr>
			<tr>
				<td>代收機構 :</td>
				<td><select id="machinery"></select></td>
			</tr>
			<tr>
				<td>代收業者合約 :</td>	
				<td><select id="machinery_contract"><option value="" >請選擇</option></select></td>	
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
				<td>*帳單發行時間種類 :</td>
				<td><select id="publish" class="required">
						<option value="">請選擇</option>
						<option value="1">每週</option>
						<option value="2">每月</option>
						<option value="3">每年</option>
						<option value="4">不固定</option></select>
			</tr>
			<tr>
				<td>*帳單入帳時間種類 :</td>
				<td><select id="enter" class="required">
						<option value="">請選擇</option>
						<option value="1">每週</option>
						<option value="2">每月</option>
						<option value="3">每年</option>
						<option value="4">不固定</option></select>
			</tr>
			<tr>
				<td>*帳單價格 :</td>
				<td><select id="bill_price" class="required">
						<option value="">請選擇 種類</option>
						<option value="1">月租</option>
						<option value="2">件計</option></select>
	
			</tr>
			<tr>
				<td>*收款時間 :</td>
				<td><select id="collection" class="required">
						<option value="">請選擇</option>
						<option value="1">每週</option>
						<option value="2">每月</option>
						<option value="3">每年</option>
						<option value="4">不固定</option></select>
			</tr>
			<tr>
				<td>*寄送條件 :</td>
				<td><select id="send_condition" class="required">
						<option value="">請選擇   寄送種類</option>
						<option value="1">實體不限次數寄送</option>
						<option value="2">實體有限次數寄送</option>
						<option value="3">只有實體帳單寄送</option></select>
			</tr>
			<tr>
				<td>*是否寄送電子帳單 :</td>
				<td><select id="send_email" class="required">
						<option value="">請選擇</option>
						<option value="1">是</option>
						<option value="2">否</option></select>
					<select id="email_publish">
						<option value="">請選擇</option>
						<option value="1">每週</option>
						<option value="2">每月</option>
						<option value="3">每年</option>
						<option value="4">不固定</option></select>
			</tr>
			<tr>
				<td>FTP 網路位置 : </td>
				<td><input id="ftp_ip" type="text" size="40" /></td>
			</tr>
			<tr>
				<td>FTP 帳號 : </td>
				<td><input id="ftp_account" type="text" size="20" /></td>
			</tr>
			<tr>
				<td>FTP 密碼 : </td>
				<td><input id="ftp_password" type="text" size="20" /></td>
			</tr>
			<tr>
				<td>FTP 繳款帳單資料路徑 : </td>
				<td><input id="ftp_path" type="text" size="60" /></td>
			</tr>
			<tr>
				<td>FTP 入帳帳單資料路徑 : </td>
				<td><input id="ftp_receive_path" type="text" size="60" /></td>
			</tr>
			<tr>	
				<td>合約備註 :</td>
				<td><textarea id="contract_remark" rows="5" cols="45"></textarea></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>