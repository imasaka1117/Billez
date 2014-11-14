/**
 * 更改電子郵件設定
 */
$(document).ready(function() {
	init();
	
	$("#update_btn").click(function() {
		if(validate()) update();
	});	
	
});
             
//更新問題
function update() {
	var path = check_ajax(ajax_path + class_name + '/update_system_set', 
						  new Array('name', 'using', 'push_times', 'sms_times', 'get_file_time', 'possible_bill_time', 'error_list_time', 'repeat_push_time'), 
						  new Array('更改成功', '伺服器忙碌中！！請在試一次'));
	if(path !== '') window.location.href = ajax_path + class_name + '/search_system_set_web	'; 
}

//初始化
function init() {
	times();
	time();
	
	data = update_ajax(ajax_path + class_name + '/search_system_set_data', id);
	
	for(i in data) {
		if(data[i] != null) $('#' + i).val(data[i]);
		if(i == 'name') $('#' + i).text(data[i]);
	}
}

//處理次數
function times() {
	var times = '<option value="">請選擇</option><option value="0">無限制</option>';
	
	for(var i = 1; i <= 10; i++) times += '<option value="' + i + '">' + i + '</option>';
	
	$('#push_times').empty().append(times);
	$('#sms_times').empty().append(times);
}

//處理時間
function time() {
	var time = '<option value="">請選擇</option>';
	time += '<option value="30">30 秒</option>';
	time += '<option value="60">1 分鐘</option>';
	time += '<option value="300">5 分鐘</option>';
	time += '<option value="600">10 分鐘</option>';
	time += '<option value="1800">30 分鐘</option>';
	time += '<option value="3600">1 小時</option>';
	time += '<option value="7200">2 小時</option>';
	time += '<option value="21600">6 小時</option>';
	time += '<option value="43200">12 小時</option>';
	time += '<option value="86400">1 天</option>';
	time += '<option value="1296000">15 天</option>';
	time += '<option value="2592000">30 天</option>';
	time += '<option value="157680000">半 年</option>';
	time += '<option value="315360000">1 年</option>';
	
	$('#get_file_time').empty().append(time);
	$('#possible_bill_time').empty().append(time);
	$('#error_list_time').empty().append(time);
	$('#repeat_push_time').empty().append(time);
}