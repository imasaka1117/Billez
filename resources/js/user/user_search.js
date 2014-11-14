/**
 * 查詢等級
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將等級對象初始化
	select_ajax(ajax_path + 'common/init_object', 'object', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search', new Array("object", "name"), 'query_div', page)
}
