/**
 * 查詢寄發電子郵件失敗紀錄
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	
	//將寄發電子郵件事件初始化
	select_ajax(ajax_path + 'common/init_email_event', 'event', '');
	
	//將寄發電子郵件時間初始化
	select_ajax(ajax_path + 'common/init_email_time', 'time', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search_email', new Array("id", "event", "email", "time"), 'query_div', page)
}