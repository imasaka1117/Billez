<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢電子郵件設定</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">查詢電子郵件設定</p>
		<table cellpadding="10">
			<tr>
				<th>目前啟用名稱 : </th>	
			</tr>
			<tr>
				<td>加入會員認證碼 : </td>	
				<td><span id="join" style="color: red"></span></td>
			</tr>
			<tr>
				<td>重複寄發加入會員認證碼 : </td>	
				<td><span id="re_join" style="color: red"></span></td>
			</tr>
			<tr>
				<td>修改資料認證碼 : </td>	
				<td><span id="alter" style="color: red"></span></td>
			</tr>
			<tr>
				<td>重複寄發修改資料認證碼 : </td>	
				<td><span id="re_alter" style="color: red"></span></td>
			</tr>
			<tr>
				<td>分享帳單 : </td>	
				<td><span id="share" style="color: red"></span></td>
			</tr>
			<tr>
				<td>推薦好友 : </td>	
				<td><span id="friend" style="color: red"></span></td>
			</tr>			
		</table>
		<table cellpadding="10">
			<tr>
				<td>設定名稱 : </td>	
				<td><select id="form_name"></select></td>
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>