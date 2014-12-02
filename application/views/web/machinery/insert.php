<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增代收機構</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$address_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
var level_value = '<?=$level_value ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">新增代收機構</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<th>代收機構資料</th>
			</tr>
			<tr>
				<td>*代收機構名稱 :</td>
				<td><input type="text" id="name" size="20" class="word,required" /></td>
			</tr>
			<tr>
				<td>*聯絡電話 :</td>
				<td><input type="text" id="telephone" size="20" maxlength="10" class="required,digits" /></td>
			</tr>
			<tr>
				<td>*代收機構等級 :</td>
				<td><select id="level_code" class="required"></select></td>
			</tr>
			<tr>
				<td>*統一編號 :</td>
				<td><input type="text" id="vat_number" size="20" maxlength="8" class="required,digits" /></td>
			</tr>
			<tr>
				<td>*聯絡地址 :</td>
				<td><select id="city" class="required" ></select><br />
					<select id="district" class="required"><option value="">請選擇 鎮/區</option></select><br />
					<input type="text" id="address" size="50" class="word,required" /></td>
			</tr>
			<tr>
				<th>主要聯絡人</th>
			</tr>
			<tr>
				<td>*姓名 : </td>
				<td><input type="text" id="main_contact_name" size="20" class="word,required" /></td>
			</tr>
			<tr>
				<td>*電話 : </td>
				<td><input type="text" id="main_contact_phone" size="20" maxlength="10" class="required,digits" /></td>
			</tr>
			<tr>
				<td>*E-mail : </td>
				<td><input type="text" id="main_contact_email" size="40" class="required,email" /></td>
			</tr>
			<tr>
				<th>次要聯絡人</th>
			</tr>
			<tr>
				<td>*姓名 : </td>
				<td><input type="text" id="second_contact_name" size="20" class="word,required" /></td>
			</tr>
			<tr>
				<td>*電話 : </td>
				<td><input type="text" id="second_contact_phone" size="20" maxlength="10" class="required,digits" /></td>
			</tr>
			<tr>
				<td>*E-mail : </td>
				<td><input type="text" id="second_contact_email" size="40" class="required,email" /></td>
			</tr>
			<tr>
				<td>備註 :</td>
				<td><textarea id="remark" rows="5" cols="45"></textarea></td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>