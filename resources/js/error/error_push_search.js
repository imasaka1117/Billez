/**
 * 查詢推播失敗紀錄
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
		$('#pay_not_read_btn').show();
		$('#receive_not_read_btn').show();
	});
	
	$("#receive_not_read_btn").click(function() {
		push_not_read(2);
	});
	
	$("#pay_not_read_btn").click(function() {
		push_not_read(1);
	});

});

//初始化
function init() {
	//將推播事件初始化
	select_ajax(ajax_path + 'common/init_event', 'event', '');
	
	//將推播時間初始化
	select_ajax(ajax_path + 'common/init_push_time', 'time', '');
	
	$('#pay_not_read_btn').hide();
	$('#receive_not_read_btn').hide();
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search_push', new Array("id", "event", "mobile_phone", "time"), 'query_div', page)
}

//推播尚未讀取會員
function push_not_read(kind) {
	var data = update_ajax(ajax_path + class_name + '/push_not_read', kind);
	
	var html = '<h3>推播完成</h3><h3>總共推播會員數量為 : ' + data + '</h3>';
	
	$('#query_div').empty().append(html);
	
}
