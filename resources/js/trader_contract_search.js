/**
 * 查詢業者合約
 */
$(document).ready(function() {
	init();

	$("#search_btn").click(function() {
		search_page_num(1);
	});
});

//初始化
function init() {
	//將業者名稱初始化
	select_ajax(ajax_path + 'trader/init_trader', 'trader_code', '');
	
	//將帳單種類初始化
	select_ajax(ajax_path + 'trader/init_bill_kind', 'bill_kind', '');
}

//查詢其他頁面
function search_page_num(page) {
	search_ajax(ajax_path + 'trader/search_contract', new Array("id", "name", "trader_code", "bill_kind"), 'query_div', page);
}
