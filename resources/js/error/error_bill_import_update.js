/**
 * 更改帳單匯入錯誤內容
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
	});	
	
});
             
//更新問題
function update() {
	var path = check_ajax(ajax_path + class_name + '/update_bill_import', 
						  new Array('time', 'result'), 
						  new Array('更改成功', '伺服器忙碌中！！請在試一次'));
	if(path !== '') location.reload(); 
}

//初始化
function init() {
	data = update_ajax(ajax_path + class_name + '/search_bill_import_data', id);
	
	for(i in data) {
		if(i == 'result') {
			$('#' + i).val(data[i]);
			continue;
		}
		
		if(i == 'data') {
			error_import_data(data[i]);
			continue;
		}
		if(data[i] != null) $('#' + i).text(data[i]);
	}
	
	if($('#result').val() == "y") {
		$('#result').attr("disabled",true);
		$('#update_btn').hide();
	}	
}

//處理錯誤資料
function error_import_data(data) {
	if($('#reason').text() == '帳單資料有空白' || $('#reason').text() == '帳單資料數不正確') {
		var temp = data.split(';');
		temp.pop();
		var line = new Array();
		var content = new Array();
		
		for(i in temp) {
			var temp_data = temp[i].split(':');
			line.push(temp_data[0]);
			content.push(temp_data[1]);
		}
		
		var count = line.length;
		var html = '<table cellpadding="5"><tr><th>錯誤行數</th><th>錯誤內容</th></tr>';
		
		for(var k = 0; k < count; k++) html += '<tr><td>' + line[k] + '</td><td>' + content[k] + '</td></tr>';

		html += '</table>'
	} else {
		var html = '<h3>錯誤訊息 : </h3><h3>' + data + '</h3>';
	}
	
	$('#query_div').empty().append(html);
}