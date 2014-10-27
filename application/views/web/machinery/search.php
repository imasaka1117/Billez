<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢代收機構</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">
var ajax_path = '<?=$index_url ?>';
var class_name = '<?=$class_name ?>';
var level_value = '<?=$level_value ?>';
</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">查詢代收機構</p>
		<table cellpadding="10">
			<tr>
				<td>代收機構編號 : </td>	
				<td><input id="id" type="text" /></td>		
				<td>代收機構名稱 : </td>	
				<td><input id="name" type="text" /></td>				
			</tr>
			<tr>
				<td>代收機構統一編號 : </td>	
				<td><input id="vat_number" type="text" /></td>
				<td>代收機構電話 : </td>	
				<td><input id="telephone" type="text" /></td>
			</tr>
			<tr>				
				<td>代收機構主要聯絡人名稱 : </td>	
				<td><input id="main_contact_name" type="text" /></td>		
				<td>代收機構等級 : </td>	
				<td><select id="level_code"></select></td>
				<td><input id="search_btn" type="button" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>