/**
 * 新增電子郵件設定
 */
$(document).ready(function() {
	init();
	
	$("#insert_btn").click(function() {
		if(validate()) insert();
	});
});

//新增電子郵件設定
function insert() {
	var path = check_ajax(ajax_path + 'operate/insert_system_set', 
						  new Array('name', 'push_times', 'sms_times', 'get_file_time', 'possible_bill_time', 'error_list_time', 'repeat_push_time'), 
						  new Array('新增成功', '設定名稱已存在', '伺服器忙碌中！！請在試一次'));
	if(path != '') location.reload(); 
}

//初始化
function init() {
	//初始化次數限制選項
	times();
	
	//初始化時間選項
	time();
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