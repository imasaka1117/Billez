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
	if(class_name === 'trader') {
		//將業者名稱初始化
		select_ajax(ajax_path + 'common/init_trader', 'trader_code', '');
		
		//將帳單種類初始化
		select_ajax(ajax_path + 'common/init_bill_kind', 'bill_kind', '');
	} else {
		//將代收機構名稱初始化
		select_ajax(ajax_path + 'common/init_machinery', 'machinery_code', '');
	}
}

//查詢其他頁面
function search_page_num(page) {
	if(class_name === 'trader') {
		var param = new Array("id", "name", "trader_code", "bill_kind");
	} else {
		var param = new Array("id", "name", "machinery_code");
	}
	
	search_ajax(ajax_path + class_name + '/search_contract', param, 'query_div', page);
}
