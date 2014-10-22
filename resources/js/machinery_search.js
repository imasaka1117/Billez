/**
 * 查詢代收機構
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將等級初始化 b 代表代收機構
	select_ajax(ajax_path + 'machinery/init_level', 'level_code', 'b');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + 'machinery/search', new Array("id", "name", "vat_number", "telephone", "level_code", "main_contact_name"), 'query_div', page)
}
