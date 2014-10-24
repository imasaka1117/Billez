/**
 * 查詢代收機構合約
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將代收機構名稱初始化
	select_ajax(ajax_path + 'machinery/init_machinery', 'machinery_code', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + 'machinery/search_contract', new Array("id", "name", "machinery_code"), 'query_div', page);
}
