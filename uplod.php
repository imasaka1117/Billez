<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8" />
<script type="text/javascript">

function comp() {
	
	var data = document.getElementById('userfile').value;
	var array = data.split(',');
	var html = '';
	
	for(var i in array) {
		html += array[i] + ': start => ' + data.indexOf(array[i]) + '... end => ' + (data.indexOf(array[i]) + array[i].length - 1) + '<br />';
	}

	document.getElementById('query_div').innerHTML = html;

	
}


</script>
</head>
<body>
	<div id="content_div">
		<p class="title_p">繳費帳單格式byte轉換</p>
		<table cellpadding="10">
			<tr>
				<td><input type="text" id="userfile" size="150"/></td>
				
			</tr>
		</table>
		<input type="button" id="import_btn" onclick="comp()" value="計算" />
	</div>
<div id="query_div">
</div>
</body>
</html>
<?php 



// $a = 'year,month,name,phone,birthday,address,id_number,email,print_date,due_date,amount,barcode_csv1,barcode_csv2,barcode_csv3,channel1,channel2,channel3,over_channel1';

// $data = explode(',', $a);

// foreach ($data as $value) {
// 	echo $value . '=> start : ' . strpos($a, $value) . ' >>>end : ' . (strpos($a, $value) + strlen($value) - 1). '<br />';
// }

// echo "======================================================<br>"
// echo date("y") . date("m") . date("d") . date("H") . date("i");

// echo substr($a, strpos($a, 'address'), strlen('address')) . '<br />';
// echo strrpos($a, 'year') . '<br />';

// $b = array('a'=>2, 'b'=>3, 'c'=>3);

// echo array_keys($b, 3)[0];

/* Socket to Air Server IP ,Port */

// include "sms2.inc";

// $server_ip = '202.39.54.130';
// $server_port = 8000;
// $TimeOut=10;

// $user_acc  = "acc";
// $user_pwd  = "pwd";
// $mobile_number= "";
// $message= "hiAir簡訊測試";


// /*建立連線*/
// $mysms = new sms2();
// $ret_code = $mysms->create_conn($server_ip, $server_port, $TimeOut, $user_acc, $user_pwd);
// $ret_msg = $mysms->get_ret_msg();




?>