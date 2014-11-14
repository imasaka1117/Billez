/**
 * 查詢推播錯誤
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將推播事件初始化
	select_ajax(ajax_path + 'common/init_event', 'event', '');
	
	//將推播時間初始化
	select_ajax(ajax_path + 'common/init_push_time', 'time', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search_push', new Array("id", "mobile_phone", "event", "time"), 'query_div', page)
}
