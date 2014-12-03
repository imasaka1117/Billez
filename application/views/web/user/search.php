<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢使用者</title>
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
		<p class="title_p">查詢使用者</p>
		<table cellpadding="10">
			<tr>
				<td>查詢對象 : </td>	
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
				<td>名稱 : </td>
				<td><input type="text" id="name" class="required"/></td>
			</tr>
			<tr>
				<td>電子郵件信箱 : </td>
				<td><input type="text" id="email" class="required,email"/></td>
			</tr>
			<tr>
				
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>