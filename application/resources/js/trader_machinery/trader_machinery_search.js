/**
 * 查詢
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將等級初始化 a 代表業者 b 代表代收機構
	select_ajax(ajax_path + 'common/init_level', 'level_code', level_value);
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + class_name + '/search', new Array("id", "name", "vat_number", "telephone", "level_code", "main_contact_name"), 'query_div', page)
}
