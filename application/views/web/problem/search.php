<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢問題</title>
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
		<p class="title_p">查詢問題</p>
		<table cellpadding="10">
			<tr>
				<td>問題編號 : </td>	
				<td><input type="text" id="id" /></td>		
				<td>問題狀態 : </td>	
				<td><select id="state"><option value="">請選擇</option><option value="y">已回覆</option><option value="n">未回覆</option></select></td>
				<td>提問範圍 : </td>	
				<td><select id="scope"><option value="">請選擇</option><option value="1">行動會員</option><option value="2">一般會員</option><option value="3">業者</option><option value="4">代收機構</option></select></td>			
			</tr>
			<tr>
				<td>提問人帳號 : </td>	
				<td><input type="text" id="asker" /></td>
				<td>提問時間 : </td>	
				<td><select id="ask_time"></select></td>
				<td></td>
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>