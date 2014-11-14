<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 問題資料</title>
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
		<p class="title_p">問題資料</p>
		<h4 id="tip_h4" style="color:red">* 號為必填</h4>
		<table cellpadding="10">
			<tr>
				<td>問題編號 : </td>	
				<td><span id="id" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>問題描述 : </td>	
				<td><span id="problem" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>提問人帳號 : </td>	
				<td><span id="asker" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>問題範圍 : </td>	
				<td><span id="scope" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>問題狀態 : </td>	
				<td><span id="state" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>提問時間 : </td>	
				<td><span id="ask_time" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>問題頁面 : </td>	
				<td><span id="page" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>評價 : </td>	
				<td><span id="star" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>回覆時間 : </td>	
				<td><span id="reply_time" style="color:red"></span></td>	
			</tr>
			<tr>
				<td>*回覆人帳號 : </td>	
				<td><input type="text" id="answer" class="required" /></td>	
			</tr>
			<tr>
				<td>*回覆內容 : </td>	
				<td><textarea id="response" style="width: 300px; height: 150px" class="required"></textarea></td>	
			</tr>
		</table>
		<input type="button" id="update_btn" value="更改" />&nbsp;<input type="button" value="返回" onclick="history.back()" />
	</div>
<div id="query_div">
</div>
</body>
</html>