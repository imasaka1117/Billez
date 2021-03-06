<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 新增使用者名稱</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$validate_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>



</head>
<body>
	<div id="content_div">
		<p class="title_p">新增使用者</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>*名稱 : </td>
				<td><input type="text" id="name" class="word,required"/></td>
			</tr>
			<tr>
				<td>*密碼 : </td>
				<td><input type="text" id="password" class="word,required"/></td>
			</tr>
			<tr>
				<td>*電子郵件 : </td>
				<td><input type="text" id="email" class="required,email"/></td>
			</tr>
			<tr>
				<td>*類型 : </td>
				<td><select id="kind" name="kind" size="0" class="required">
					<option value="">請選擇</option>
					<option value="1">OP</option>
					<option value="2">客服</option>
					<option value="3">管理者</option>
					<option value="4">業者</option>
					<option value="5">代收機構</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>權限選擇:</td>
				<td><input name="selectAll" id="selectAll" type="button" value="全選"/>
					<input name="cancelAll" id="cancelAll" type="button" value="全刪除"/></td>
			</tr>
			<tr>
				<th>業者</th>
				<td><input type="checkbox" name="function_authority" id="insert_trader" value="y" />新增業者<br />
					<input type="checkbox" name="function_authority" id="insert_trader_contract" value="y" />新增業者合約<br />
					<input type="checkbox" name="function_authority" id="search_trader" value="y" />查詢業者<br />
					<input type="checkbox" name="function_authority" id="search_trader_contract" value="y" />查詢業者合約<br />
					<input type="checkbox" name="function_authority" id="export_trader" value="y" />匯出業者<br />
					<input type="checkbox" name="function_authority" id="export_trader_report" value="y" />匯出業者報表</td>
			</tr>
			<tr>
				<th>代收機構</th>
				<td><input type="checkbox" name="function_authority" id="insert_machinery" value="y"/>新增代收機構<br />
					<input type="checkbox" name="function_authority" id="insert_machinery_contract" value="y" />新增代收機構合約<br />
					<input type="checkbox" name="function_authority" id="search_machinery" value="y" />查詢代收機構<br />
					<input type="checkbox" name="function_authority" id="search_machinery_contract" value="y"/>查詢代收機構合約<br />
					<input type="checkbox" name="function_authority" id="export_machinery" value="y"/>匯出代收機構<br />
					<input type="checkbox" name="function_authority" id="export_machinery_report" value="y"/>匯出代收機構報表</td>
			</tr>
			<tr>
				<th>帳單</th>
				<td><input type="checkbox" name="function_authority" id="insert_bill_kind" value="y"/>新增帳單種類<br />
					<input type="checkbox" name="function_authority" id="insert_bill_basis" value="y"/>新增帳單依據<br />
					<input type="checkbox" name="function_authority" id="search_bill" value="y"/>查詢帳單<br />
					<input type="checkbox" name="function_authority" id="insert_pay_bill_set" value="y"/>新增繳費單格式<br />
					<input type="checkbox" name="function_authority" id="insert_receive_bill_set" value="y"/>新增入帳單格式<br />
					<input type="checkbox" name="function_authority" id="update_pay_bill_set" value="y"/>修改繳費單格式<br />
					<input type="checkbox" name="function_authority" id="update_receive_bill_set" value="y"/>修改入帳單格式<br />
					<input type="checkbox" name="function_authority" id="insert_customer_pay_bill_set" value="y"/>新增客製繳費格式<br />
					<input type="checkbox" name="function_authority" id="update_customer_pay_bill_set" value="y"/>修改客製繳費格式<br />
					<input type="checkbox" name="function_authority" id="import_pay_bill" value="y"/>匯入繳費帳單<br />
					<input type="checkbox" name="function_authority" id="import_receive_bill" value="y"/>匯入入賬帳單<br />
					<input type="checkbox" name="function_authority" id="push_bill" value="y"/>推播帳單</td>
			</tr>
			<tr>
				<th>訂閱</th>
				<td><input type="checkbox" name="function_authority" id="search_subscribe" value="y"/>查詢訂閱<br />
					<input type="checkbox" name="function_authority" id="update_trader_subscribe_state" value="y"/>業者訂閱狀態更改<br />
					<input type="checkbox" name="function_authority" id="update_trader_machinery" value="y"/>業者代收機構更改</td>
			</tr>
			<tr>
				<th>行動會員</th>
				<td><input type="checkbox" name="function_authority" id="search_action_member" value="y"/>查詢行動會員<br />
					<input type="checkbox" name="function_authority" id="export_action_member" value="y"/>匯出行動會員</td>
			</tr>
			<tr>
				<th>一般會員</th>
				<td><input type="checkbox" name="function_authority" id="search_normal_member" value="y"/>查詢一般會員</td>
			</tr>
			<tr>
				<th>等級</th>
				<td><input type="checkbox" name="function_authority" id="insert_level" value="y"/>新增等級<br />
					<input type="checkbox" name="function_authority" id="search_level" value="y"/>查詢等級</td>
				
			</tr>
			<tr>
				<th>促銷優惠</th>
				<td><input type="checkbox" name="function_authority" id="insert_promotion" value="y"/>新增促銷優惠<br />
					<input type="checkbox" name="function_authority" id="search_promotion" value="y"/>查詢促銷優惠<br />
					<input type="checkbox" name="function_authority" id="send_promotion_email" value="y"/>寄發電子報</td>
			</tr>
			<tr>
				<th>問題記錄</th>
				<td><input type="checkbox" name="function_authority" id="insert_problem" value="y"/>新增問題<br />
					<input type="checkbox" name="function_authority" id="search_problem" value="y"/>查詢問題</td>
			</tr>
			<tr>
				<th>錯誤記錄</th>
				<td><input type="checkbox" name="function_authority" id="bill_import_error" value="y"/>帳單匯入錯誤<br />
					<input type="checkbox" name="function_authority" id="push_error" value="y"/>推播錯誤<br />
					<input type="checkbox" name="function_authority" id="sms_error" value="y"/>簡訊錯誤<br />
					<input type="checkbox" name="function_authority" id="email_error" value="y"/>電子郵件錯誤<br />
					<input type="checkbox" name="function_authority" id="system_error" value="y"/>系統錯誤</td>
			</tr>
			<tr>
				<th>操作設定</th>
				<td><input type="checkbox" name="function_authority" id="search_operator" value="y"/>查詢操作<br />
					<input type="checkbox" name="function_authority" id="insert_system_set" value="y"/>新增系統設定<br />
					<input type="checkbox" name="function_authority" id="search_system_set" value="y"/>查詢系統設定<br />
					<input type="checkbox" name="function_authority" id="scheduling_set" value="y"/>排程設定<br />
					<input type="checkbox" name="function_authority" id="insert_email_set" value="y"/>新增電子郵件設定<br />
					<input type="checkbox" name="function_authority" id="search_email_set" value="y"/>查詢電子郵件設定<br />
					<input type="checkbox" name="function_authority" id="insert_sms_set" value="y"/>新增簡訊設定<br />
					<input type="checkbox" name="function_authority" id="search_sms_set" value="y"/>檢查簡訊設定</td>
			</tr>
			<tr>
				<th>系統使用者</th>
				<td><input type="checkbox" name="function_authority" id="search_user" value="y"/>查詢使用者<br />
					<input type="checkbox" name="function_authority" id="insert_user" value="y"/>新增使用者</td>
			</tr>
		</table>
		<input type="button" id="insert_btn" value="新增" />
	</div>
<div id="query_div">
</div>
</body>
</html>