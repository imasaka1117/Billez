<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Billez 查詢代收機構合約</title>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="<?=$base_css ?>" />
<script type="text/javascript" src="<?=$jquery_js ?>"></script>
<script type="text/javascript" src="<?=$function_js ?>"></script>
<script type="text/javascript" src="<?=$js_path ?><?=$now_use ?>"></script>
<script type="text/javascript">var ajax_path = '<?=$index_url ?>';</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">查詢代收機構合約</p>
		<table cellpadding="10">
			<tr>
				<td>代收機構合約編號 : </td>
				<td><input id="id" type="text" /></td>
				<td>代收機構合約名稱 : </td>
				<td><input id="name" type="text" /></td>
				<td>代收機構名稱 : </td>
				<td><select id="machinery_code"></select></td>
				<td><input type="button" id="search_btn" value="查詢" /></td>
			</tr>
		</table>
	</div>
<div id="query_div">
</div>
</body>
</html>