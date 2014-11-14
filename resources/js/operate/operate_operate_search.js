/**
 * 查詢寄發簡訊失敗紀錄
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將操作種類初始化
	select_ajax(ajax_path + 'common/init_operate_kind', 'operator', '');
	
	//將操作者初始化
	select_ajax(ajax_path + 'common/init_user', 'user', '');
	
	//將操作時間初始化
	select_ajax(ajax_path + 'common/init_operate_time', 'time', '');
	
	//將操作資料表初始化
	select_ajax(ajax_path + 'common/init_table', 'table', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search_operate', new Array('operator', 'user', 'time', 'table'), 'query_div', page)
}